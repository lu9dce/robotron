#!/bin/bash
#
if ! [ -x "$(command -v xterm)" ]; then
  echo 'Error: xterm is not installed.' >&2
  exit 1
fi
if ! [ -x "$(command -v php)" ]; then
  echo 'Error: php is not installed.' >&2
  exit 1
fi
if ! [ -x "$(command -v jtdx)" ]; then
  echo 'Error: jtdx is not installed.' >&2
  exit 1
fi
xterm \
-xrm 'XTerm.vt100.allowTitleOps: false' \
-xrm 'xterm*ScrollBar: off' \
-xrm 'XTerm.vt100.renderFont: true' \
-xrm 'XTerm.vt100.faceName: Liberation Mono:size=10:antialias=true' \
-T "ROBOTRO-2084 by LU9DCE" \
-fg white \
-bg black \
-geom 80x20 \
-e php -f bin/robot.php
