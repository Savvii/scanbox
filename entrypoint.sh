#!/bin/bash
set -e

ACTION_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CODE_DIR="$INPUT_CODE_DIR"
OPENAI_API_KEY="$INPUT_OPENAI_API_KEY"
OPENAI_BASE_URL="$INPUT_OPENAI_BASE_URL"
CUSTOM_PROMPT="$INPUT_CUSTOM_PROMPT"
MODEL="$INPUT_MODEL"

# Resolve code dir relative to workflow run (GITHUB_WORKSPACE)
if [ -d "$GITHUB_WORKSPACE" ] && [[ ! "$CODE_DIR" = /* ]]; then
    CODE_DIR="$GITHUB_WORKSPACE/$CODE_DIR"
fi

if [ ! -d "$CODE_DIR" ]; then
    echo "Error: code-dir '$CODE_DIR' does not exist"
    exit 1
fi

# Build docker args array
DOCKER_ARGS=("-v" "$CODE_DIR:/app")

if [ -n "$OPENAI_API_KEY" ]; then
    if [ -n "$OPENAI_BASE_URL" ]; then
        CONFIG_JSON=$(printf '{"$schema":"https://opencode.ai/config.json","provider":{"openai-compatible":{"npm":"@ai-sdk/openai-compatible","options":{"baseURL":"%s","apiKey":"%s"},"models":{"%s":{"name":"%s"}}}}}' "$OPENAI_BASE_URL" "$OPENAI_API_KEY" "$MODEL" "$MODEL")
    else
        CONFIG_JSON=$(printf '{"$schema":"https://opencode.ai/config.json","provider":{"openai-compatible":{"npm":"@ai-sdk/openai-compatible","options":{"baseURL":"https://api.openai.com/v1","apiKey":"%s"},"models":{"%s":{"name":"%s"}}}}}' "$OPENAI_API_KEY" "$MODEL" "$MODEL")
    fi

    echo "$CONFIG_JSON" > "$ACTION_DIR/opencode-config.json"
    DOCKER_ARGS+=("-v" "$ACTION_DIR/opencode-config.json:/app/.config/opencode/config.json")
    DOCKER_ARGS+=("-e" "OPENAI_API_KEY=$OPENAI_API_KEY")
fi

if [ -n "$CUSTOM_PROMPT" ] && [ -f "$CUSTOM_PROMPT" ]; then
    DOCKER_ARGS+=("-v" "$CUSTOM_PROMPT:/prompt.md")
fi

echo "Running scanbox security audit on: $CODE_DIR"
docker run --rm "${DOCKER_ARGS[@]}" ghcr.io/savvii/scanbox:latest

# Copy the output to the action directory for artifact upload
cp "$CODE_DIR/scanbox.json" "$ACTION_DIR/scanbox.json"
echo "Audit complete. Results saved to $ACTION_DIR/scanbox.json"
