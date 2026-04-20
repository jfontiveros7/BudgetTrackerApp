"""
Budget Tracker App - FastAPI Server
Exposes the budget agent as an API and serves a dark-themed chat UI.
"""

from __future__ import annotations

import os

from dotenv import load_dotenv
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import HTMLResponse
from pydantic import BaseModel

try:
    from .agent import run_agent
except ImportError:  # pragma: no cover - supports direct execution
    from agent import run_agent

load_dotenv()

app = FastAPI(title="Budget Tracker App Agent API", version="1.0.0")
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

sessions: dict[str, list[dict[str, str]]] = {}


class ChatRequest(BaseModel):
    message: str
    session_id: str = "default"


class ChatResponse(BaseModel):
    reply: str
    session_id: str


@app.post("/chat", response_model=ChatResponse)
async def chat(request: ChatRequest) -> ChatResponse:
    try:
        if request.session_id not in sessions:
            sessions[request.session_id] = []
        reply = await run_agent(request.message, sessions[request.session_id])
        sessions[request.session_id].append({"role": "user", "content": request.message})
        sessions[request.session_id].append({"role": "assistant", "content": reply})
        return ChatResponse(reply=reply, session_id=request.session_id)
    except Exception as exc:
        raise HTTPException(status_code=500, detail=str(exc)) from exc


@app.delete("/session/{session_id}")
async def clear_session(session_id: str) -> dict[str, str]:
    if session_id in sessions:
        del sessions[session_id]
        return {"status": "cleared"}
    return {"status": "not_found"}


@app.get("/health")
async def health() -> dict[str, str]:
    return {"status": "healthy", "service": "Budget Tracker App Agent"}


@app.get("/", response_class=HTMLResponse)
async def chat_ui() -> str:
    return """
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget Tracker App - Konticode Labs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #e2e8f0;
               height: 100vh; display: flex; flex-direction: column; }
        header { background: #1e293b; padding: 16px 24px; border-bottom: 1px solid #334155;
                 display: flex; align-items: center; gap: 12px; }
        header h1 { font-size: 18px; color: #3b82f6; }
        header span { font-size: 12px; color: #64748b; }
        #chat { flex: 1; overflow-y: auto; padding: 24px; display: flex;
                flex-direction: column; gap: 16px; }
        .msg { max-width: 75%; padding: 12px 16px; border-radius: 12px;
               line-height: 1.5; font-size: 14px; white-space: pre-wrap; }
        .user { align-self: flex-end; background: #3b82f6; color: #fff; }
        .bot { align-self: flex-start; background: #1e293b; border: 1px solid #334155; }
        #input-area { background: #1e293b; padding: 16px 24px; border-top: 1px solid #334155;
                      display: flex; gap: 12px; }
        #input-area input { flex: 1; background: #0f172a; border: 1px solid #334155;
                           border-radius: 8px; padding: 12px 16px; color: #e2e8f0;
                           font-size: 14px; outline: none; }
        #input-area input:focus { border-color: #3b82f6; }
        #input-area button { background: #3b82f6; color: #fff; border: none;
                            border-radius: 8px; padding: 12px 24px; font-weight: 600; cursor: pointer; }
        #input-area button:hover { background: #2563eb; }
    </style>
</head>
<body>
    <header><h1>Budget Tracker App</h1><span>by Konticode Labs</span></header>
    <div id="chat">
        <div class="msg bot">Welcome! I can track expenses, manage budgets, and show spending summaries.</div>
    </div>
    <div id="input-area">
        <input id="msg" placeholder="e.g. Add $45 for Groceries..." autofocus />
        <button id="send" onclick="sendMessage()">Send</button>
    </div>
    <script>
        const chatDiv = document.getElementById('chat');
        const msgInput = document.getElementById('msg');
        const sendBtn = document.getElementById('send');
        const sid = 'session_' + Date.now();
        msgInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendMessage(); });
        async function sendMessage() {
            const text = msgInput.value.trim();
            if (!text) return;
            appendMsg(text, 'user');
            msgInput.value = '';
            sendBtn.disabled = true;
            try {
                const res = await fetch('/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: text, session_id: sid }),
                });
                const data = await res.json();
                appendMsg(data.reply, 'bot');
            } catch {
                appendMsg('Error: Could not reach the agent.', 'bot');
            }
            sendBtn.disabled = false;
            msgInput.focus();
        }
        function appendMsg(text, role) {
            const div = document.createElement('div');
            div.className = 'msg ' + role;
            div.textContent = text;
            chatDiv.appendChild(div);
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }
    </script>
</body>
</html>
"""


if __name__ == "__main__":
    import uvicorn

    uvicorn.run(
        "agent_sdk.server:app",
        host=os.getenv("HOST", "0.0.0.0"),
        port=int(os.getenv("PORT", 8000)),
        reload=True,
    )
