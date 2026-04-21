#!/bin/sh
set -eu

PORT_VALUE="${PORT:-8080}"
exec php -S "0.0.0.0:${PORT_VALUE}" -t public public/index.php
