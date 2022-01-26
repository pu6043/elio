#!/bin/bash

# set errexit to halt the script when a command fails
set -e

# Traps
trap 'cecho $error "\"${last_command}\" command failed with exit code $?."' ERR
trap 'last_command=$current_command; current_command=$BASH_COMMAND' DEBUG
trap 'cecho $comment "Ending script execution"' EXIT

# Colored Output
cecho () {
  local _color=$1; shift
  echo "$(tput setaf $_color)$@$(tput sgr0)"
}

# Color Variables
info=6; error=1; warning=3; success=2; verbose=4; debug=5; comment=7; expired=8;

# Error Display
err () {
  cecho $error "$@" >&2;
}

cecho $info '

   ___ | |(_)  ___
  / _ \| || | / _ \
 |  __/| || || (_) |
  \___||_||_| \___/

'