from __future__ import annotations

from agents import Agent

from .tools import (
    add_transaction,
    analyze_monthly_trends,
    create_budget,
    delete_transaction,
    finance_snapshot_for_insights,
    get_forecast,
    get_budget_status,
    get_overspending_risk,
    get_spending_summary,
    get_subscription_list,
    get_top_categories,
    list_categories,
    list_transactions,
    rename_category,
    summarize_budget_health,
    update_budget,
    update_transaction_description,
)


transaction_agent = Agent(
    name="Transaction Agent",
    model="gpt-5-mini",
    handoff_description="Use for adding, listing, deleting, and correcting transactions.",
    instructions=(
        "You handle adding, listing, and deleting transactions. "
        "Always confirm the amount and category before adding, and store the merchant name when provided. "
        "Use tools whenever the user asks for live transaction data or changes."
    ),
    tools=[
        add_transaction,
        list_transactions,
        update_transaction_description,
        delete_transaction,
    ],
)


budget_agent = Agent(
    name="Budget Agent",
    model="gpt-5-mini",
    handoff_description="Use for budget creation, updates, and status checks.",
    instructions=(
        "You manage budget creation, updates, and status checks. "
        "Budget updates should use the category name the user gives you. "
        "Alert when spending exceeds 80% of a budget limit."
    ),
    tools=[
        create_budget,
        update_budget,
        get_budget_status,
    ],
)


analytics_agent = Agent(
    name="Analytics Agent",
    model="gpt-5-mini",
    handoff_description="Use for spending insights, summaries, trends, and forecasts.",
    instructions=(
        "You provide spending insights, trends, and forecasts. "
        "Use clear numbers and percentages."
    ),
    tools=[
        get_spending_summary,
        get_forecast,
        get_top_categories,
        get_subscription_list,
        get_overspending_risk,
        analyze_monthly_trends,
    ],
)


category_agent = Agent(
    name="Category Agent",
    model="gpt-5-mini",
    handoff_description="Use for category organization, listing categories, and renaming categories.",
    instructions=(
        "You are the Category Agent. "
        "Help the user clean up, inspect, and normalize category names."
    ),
    tools=[list_categories, rename_category],
)


insights_agent = Agent(
    name="Insights Agent",
    model="gpt-5",
    handoff_description="Use for high-level summaries, recommendations, and GPT-style financial insights.",
    instructions=(
        "You are the Insights Agent. "
        "Use the provided finance snapshot and any relevant tools to generate practical, specific, non-judgmental recommendations. "
        "Prefer concise summaries with clear next steps."
    ),
    tools=[
        finance_snapshot_for_insights,
        summarize_budget_health,
        get_forecast,
        get_top_categories,
        get_subscription_list,
        get_overspending_risk,
        analyze_monthly_trends,
    ],
)
