#!/usr/bin/bash
SCRIPT_PATH="$( cd "$(dirname "$0")" >/dev/null 2>&1 ; pwd -P )"
DIRS=(
'../frontend/web/images/'
'../frontend/web/uploads/'
'../backend/web/img/'
'../backend/web/uploads/'
)

for DIR in ${DIRS[@]}; do
  # converting JPEG images
  find $SCRIPT_PATH/../$DIR -type f -and \( -iname "*.jpg" -o -iname "*.jpeg" \) \
  -exec bash -c '
  webp_path=$(sed 's/\.[^.]*$/.webp/' <<< "$0");
  if [ ! -f "$webp_path" ]; then
  cwebp -quiet -q 90 "$0" -o "$webp_path";
  fi;' {} \;

  # converting PNG images
  find $SCRIPT_PATH/../$DIR -type f -and -iname "*.png" \
  -exec bash -c '
  webp_path=$(sed 's/\.[^.]*$/.webp/' <<< "$0");
  if [ ! -f "$webp_path" ]; then
  cwebp -quiet -lossless "$0" -o "$webp_path";
  fi;' {} \;
done

