#!/bin/bash

# Configuration
HOST="192.168.1.1"  # e.g., Unifi OS
USER="user"             # e.g., controll device ssh
PASS="admin"

# Install sshpass if not present (uncomment on first run)
# apt install -y sshpass  # Debian/Ubuntu
# SSH and run the speedtest script remotely (ignores host key check for automation)
sshpass -p "$PASS" ssh -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null "$USER@$HOST" '
     wget -O speedtest-cli https://raw.githubusercontent.com/sivel/speedtest-cli/master/speedtest.py
     chmod +x speedtest-cli
     OUTPUT=$(./speedtest-cli)
     curl -s -X POST -d "output=$OUTPUT" https://mywebserver/speedtest/speedtest.php
     rm -f speedtest-cli  # Optional: cleanup
 '
