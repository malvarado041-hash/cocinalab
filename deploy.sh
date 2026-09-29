#!/usr/bin/env bash
# Uso: ./deploy.sh [rama_origen]   (por defecto: tercera)
#
# Actualiza la rama `deploy` con el contenido de la rama origen y la sube.
# Railway solo observa `deploy`, así que los pushes de tus compañeros a otras
# ramas NO provocan redespliegues. Solo se despliega cuando TÚ corres esto.
set -euo pipefail

FROM="${1:-tercera}"
DEPLOY="deploy"

# Evita desplegar dejando trabajo sin commitear por accidente
if [ -n "$(git status --porcelain)" ]; then
  echo "❌ Tienes cambios sin commitear. Haz commit o stash antes de desplegar."
  exit 1
fi

git fetch origin
echo "🚀 Desplegando origin/$FROM → $DEPLOY ..."
git push origin "origin/$FROM:$DEPLOY"
echo "✅ Deploy lanzado. Railway redesplegará la rama '$DEPLOY' en unos minutos."
