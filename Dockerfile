FROM node:22-slim

# Install git (required for OpenCode)
RUN apt-get update && apt-get install -y git ripgrep && rm -rf /var/lib/apt/lists/*

# Install the tool globally
RUN npm install -g opencode-ai

# Set the working directory
WORKDIR /app
COPY prompt.md /prompt.md

# Point OpenCode to look for config here by default
ENV XDG_CONFIG_HOME=/app/.config

# Default command
ENTRYPOINT ["sh", "-c", "opencode run \"$(cat /prompt.md)\""]
