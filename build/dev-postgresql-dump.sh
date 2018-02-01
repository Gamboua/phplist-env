#!/bin/bash
set -e

pg_restore -U phplist -h localhost -d phplist /docker-entrypoint-initdb.d/lissm001_new.backup