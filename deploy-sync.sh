#!/bin/bash
set -e
cd "$(dirname "$0")"
echo "==> [deploy-sync] Syncing public/ assets to public_html ..."
rsync -a --exclude=index.php --exclude=.htaccess public/ ../
echo "==> [deploy-sync] Rebuilding Tailwind CSS ..."
if [ -x "$HOME/tw-tools/tailwindcss" ]; then
  mkdir -p "$HOME/tw-tools/tmp"
  TMPDIR="$HOME/tw-tools/tmp" "$HOME/tw-tools/tailwindcss" -i resources/css/app-tw.css -o public/tw-build.css --minify
  cp public/tw-build.css ../tw-build.css
else
  echo "    (skipped: $HOME/tw-tools/tailwindcss not found)"
fi
echo "==> [deploy-sync] Clearing Laravel caches ..."
php artisan view:clear
php artisan config:clear
php artisan route:clear
echo "==> [deploy-sync] Done."
