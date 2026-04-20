from __future__ import annotations

import json
import os
import sys

from .agent import run_agent_sync


def main() -> None:
    args = sys.argv[1:]
    output_json = False

    if args and args[0] == "--json":
        output_json = True
        args = args[1:]

    if len(args) < 1:
        print("Usage: python -m agent_sdk.main [--json] \"Your budgeting question here\"")
        raise SystemExit(1)

    user_input = " ".join(args)
    output = run_agent_sync(user_input)
    if output_json:
        print(json.dumps({"output": output}))
    else:
        print(output)
    sys.stdout.flush()
    sys.stderr.flush()
    os._exit(0)


if __name__ == "__main__":
    main()
