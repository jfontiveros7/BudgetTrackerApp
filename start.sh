#!/bin/bash
set -eu

PORT="${PORT:-8080}"

exec php -S "0.0.0.0:${PORT}" -t public
