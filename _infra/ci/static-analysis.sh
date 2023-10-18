#!/bin/bash
set -euo pipefail
IFS=$'\n\t'

echo "################################################################################"
echo "# PHP PSALM                                                                    #"
echo "################################################################################"

echo "" && ./vendor/bin/psalm \
    --no-cache \
    --no-file-cache \
    --php-version=8.2 \
    --no-reflection-cache \
    --show-info=true && echo ""

echo "################################################################################"
echo "# Larastan                                                                     #"
echo "################################################################################"

echo "" && ./vendor/bin/phpstan analyse --configuration=phpstan.neon --memory-limit=2G
