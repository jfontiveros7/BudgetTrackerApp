"""Backend API tests for Budget Tracker Landing API.

Covers:
- Health endpoints (/api/, /api/status)
- Leads CRUD: POST /api/leads, GET /api/leads
- Validation cases (invalid email -> 422)
"""

import os
import uuid
import pytest
import requests

BASE_URL = os.environ.get("REACT_APP_BACKEND_URL", "https://konti-hero.preview.emergentagent.com").rstrip("/")
API = f"{BASE_URL}/api"


@pytest.fixture(scope="module")
def session():
    s = requests.Session()
    s.headers.update({"Content-Type": "application/json"})
    return s


# ---------- Existing endpoints health ----------
class TestHealth:
    def test_root(self, session):
        r = session.get(f"{API}/", timeout=15)
        assert r.status_code == 200
        data = r.json()
        assert "message" in data
        assert isinstance(data["message"], str)

    def test_status_create_and_list(self, session):
        unique = f"TEST_{uuid.uuid4().hex[:8]}"
        r = session.post(f"{API}/status", json={"client_name": unique}, timeout=15)
        assert r.status_code == 200
        created = r.json()
        assert created["client_name"] == unique
        assert "id" in created and isinstance(created["id"], str)

        r2 = session.get(f"{API}/status", timeout=15)
        assert r2.status_code == 200
        items = r2.json()
        assert isinstance(items, list)
        assert any(x.get("client_name") == unique for x in items)


# ---------- Leads ----------
class TestLeads:
    def test_create_lead_valid(self, session):
        payload = {
            "name": "TEST_Lead Person",
            "email": f"test_{uuid.uuid4().hex[:8]}@example.com",
            "company_size": "11-50",
            "plan_interest": "growth",
            "message": "Interested in demo",
        }
        r = session.post(f"{API}/leads", json=payload, timeout=15)
        assert r.status_code == 201, r.text
        data = r.json()
        # Validate response fields
        assert data["name"] == payload["name"]
        assert data["email"] == payload["email"]
        assert data["company_size"] == payload["company_size"]
        assert data["plan_interest"] == payload["plan_interest"]
        assert data["message"] == payload["message"]
        assert "id" in data and isinstance(data["id"], str)
        assert "created_at" in data

        # Verify persistence via list
        r2 = session.get(f"{API}/leads", timeout=15)
        assert r2.status_code == 200
        leads = r2.json()
        assert isinstance(leads, list)
        assert any(l["email"] == payload["email"] for l in leads)

    def test_create_lead_minimal_valid(self, session):
        payload = {
            "name": "TEST_Min",
            "email": f"min_{uuid.uuid4().hex[:8]}@example.com",
        }
        r = session.post(f"{API}/leads", json=payload, timeout=15)
        assert r.status_code == 201, r.text
        d = r.json()
        assert d["name"] == payload["name"]
        assert d["email"] == payload["email"]
        assert d.get("company_size") is None
        assert d.get("plan_interest") is None
        assert d.get("message") is None

    def test_create_lead_invalid_email(self, session):
        r = session.post(
            f"{API}/leads",
            json={"name": "Bad", "email": "not-an-email"},
            timeout=15,
        )
        assert r.status_code == 422

    def test_create_lead_missing_name(self, session):
        r = session.post(
            f"{API}/leads",
            json={"email": "ok@example.com"},
            timeout=15,
        )
        assert r.status_code == 422

    def test_create_lead_empty_name(self, session):
        r = session.post(
            f"{API}/leads",
            json={"name": "", "email": "ok@example.com"},
            timeout=15,
        )
        assert r.status_code == 422

    def test_list_leads(self, session):
        r = session.get(f"{API}/leads", timeout=15)
        assert r.status_code == 200
        data = r.json()
        assert isinstance(data, list)
        if data:
            sample = data[0]
            for key in ("id", "name", "email", "created_at"):
                assert key in sample

    def test_list_leads_limit_invalid(self, session):
        r = session.get(f"{API}/leads?limit=0", timeout=15)
        assert r.status_code == 400
        r2 = session.get(f"{API}/leads?limit=9999", timeout=15)
        assert r2.status_code == 400
