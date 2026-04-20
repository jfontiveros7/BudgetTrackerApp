from __future__ import annotations

from agents import Agent, handoff

from .specialists import (
    analytics_agent,
    budget_agent,
    category_agent,
    insights_agent,
    transaction_agent,
)


budget_coordinator_agent = Agent(
    name="Budget Tracker App",
    model="gpt-5",
    instructions=(
        "You are the Budget Tracker App assistant. "
        "Route requests to the right specialist: "
        "Transactions to Transaction Agent, Budgets to Budget Agent, "
        "Analytics or insights to Analytics Agent, category cleanup to Category Agent, "
        "and broader financial recommendations to Insights Agent. "
        "Be concise, use dollar formatting, and proactively warn about overspending."
    ),
    handoffs=[
        handoff(transaction_agent),
        handoff(budget_agent),
        handoff(analytics_agent),
        handoff(category_agent),
        handoff(insights_agent),
    ],
)
