import sys
import json
import pandas as pd
import os
from agent_sdk.db import get_db_connection

import re
def guess_column(col):
    name = col.lower().replace('_', ' ').replace('-', ' ')
    if re.search(r'email', name):
        return 'email'
    if re.search(r'full.?name|name', name):
        return 'name'
    if re.search(r'amount|value|total', name):
        return 'amount'
    if re.search(r'category|type', name):
        return 'category'
    if re.search(r'desc|memo|note', name):
        return 'description'
    if re.search(r'date|day', name):
        return 'date'
    if re.search(r'transaction.?type', name):
        return 'type'
    if re.search(r'role', name):
        return 'role'
    return None

def smart_map_and_insert(df):
    col_map = {}
    for col in df.columns:
        mapped = guess_column(col)
        if mapped:
            col_map[mapped] = col
    inserted = 0
    errors = []
    conn = get_db_connection()
    for _, row in df.iterrows():
        try:
            # Insert transaction
            if all(k in col_map for k in ['amount', 'category', 'description', 'date', 'type']):
                sql = "INSERT INTO transactions (user_id, category, description, amount, type, transaction_date) VALUES (%s, %s, %s, %s, %s, %s)"
                conn.execute(sql, (1, row[col_map['category']], row[col_map['description']], float(row[col_map['amount']]), row[col_map['type']], row[col_map['date']]))
                inserted += 1
            # Insert user
            elif all(k in col_map for k in ['email', 'name']):
                sql = "INSERT INTO users (name, email, role) VALUES (%s, %s, %s)"
                role = row[col_map['role']] if 'role' in col_map else 'user'
                conn.execute(sql, (row[col_map['name']], row[col_map['email']], role))
                inserted += 1
            # Insert category
            elif all(k in col_map for k in ['category']):
                sql = "INSERT IGNORE INTO categories (name, type) VALUES (%s, %s)"
                cat_type = row[col_map['type']] if 'type' in col_map else 'expense'
                conn.execute(sql, (row[col_map['category']], cat_type))
                inserted += 1
        except Exception as e:
            errors.append(str(e))
    return {"inserted": inserted, "errors": errors, "mapping": col_map}

def main():
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No file provided"}))
        return
    file_path = sys.argv[1]
    if not os.path.exists(file_path):
        print(json.dumps({"error": "File not found"}))
        return
    try:
        df = pd.read_excel(file_path)
    except Exception as e:
        print(json.dumps({"error": f"Failed to read Excel: {e}"}))
        return
    result = smart_map_and_insert(df)
    print(json.dumps(result))

if __name__ == "__main__":
    main()
