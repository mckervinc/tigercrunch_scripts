#!/bin/bash
mysql -u root -phunger <<EOF 
USE hunger_1994ampz;
TRUNCATE FreeFood;
EOF

