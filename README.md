# Scanbox

A Docker-based tool for automated security auditing of codebases using AI-powered analysis.

## Description

OpenCode Security Audit leverages OpenCode AI to scan your code for critical security vulnerabilities. It focuses on exploitable issues like Remote Code Execution (RCE), SQL Injection (SQLi), Cross-Site Scripting (XSS), and more, providing a structured JSON report for easy integration into CI/CD pipelines or manual reviews.

The tool is designed to be lightweight, running in a Docker container, and configurable for different AI providers.

## Features

- **AI-Powered Auditing**: Uses advanced language models to detect vulnerabilities with high accuracy.
- **Dockerized**: Easy to deploy without affecting your local environment.
- **Comprehensive Coverage**: Identifies RCE, SQLi, Auth Bypass, File Operations, XXE, XSS, CSRF, Open Redirects, and Information Disclosure.
- **Structured Output**: Generates a JSON report with CVSS scores, file locations, and code snippets.
- **Configurable**: Supports multiple AI providers via configuration files.
- **Scope Filtering**: Reports only directly exploitable vulnerabilities on default configurations without authentication.

## Prerequisites

- Docker installed on your system.
- A directory containing the code you want to audit.
- (Optional) Access to an AI provider like LlamaCPP for custom configurations.

## Installation

### Using a Pre-built Image

1. Prepare your code directory: Ensure your code is in a directory (e.g., `$YOURCODEDIR`) and contains a `.gitignore` file to exclude unwanted files from the scan (OpenCode uses ripgrep, which respects `.gitignore`).

2. Run the audit:
   ```bash
   docker run --rm -it \
     -v "$(pwd)/opencode-config.json:/app/.config/opencode/config.json" \
     -v "$(pwd)/$YOURCODEDIR:/app" \
     ghcr.io/savvii/scanbox:latest

   Optional: To use a custom prompt, add `-v "$(pwd)/your-prompt.md:/prompt.md"` to the command.

   Replace `$YOURCODEDIR` with the path to your code directory.

3. Check the output: The tool will generate `scanbox.json` in the root of your code directory.

### Manual Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/Savvii/scanbox.git
   cd scanbox
   ```

2. Build the Docker image:
   ```bash
   docker build -t scanbox .
   ```

3. Prepare your code directory: Ensure your code is in a directory (e.g., `$YOURCODEDIR`) and contains a `.gitignore` file to exclude unwanted files from the scan (OpenCode uses ripgrep, which respects `.gitignore`).

4. Run the audit:
   ```bash
   docker run --rm -it \
     -v "$(pwd)/opencode-config.json:/app/.config/opencode/config.json" \
     -v "$(pwd)/$YOURCODEDIR:/app" \
     scanbox
   ```

   Optional: To use a custom prompt, add `-v "$(pwd)/your-prompt.md:/prompt.md"` to the command.

   Replace `$YOURCODEDIR` with the path to your code directory.

5. Check the output: The tool will generate `scanbox.json` in the root of your code directory.



## Configuration

The tool uses `opencode-config.json` for AI provider settings. In the example, it's configured for LlamaCPP. You can modify it to use other providers like OpenAI or Anthropic.

Example configuration:
```json
{
  "$schema": "https://opencode.ai/config.json",
  "provider": {
    "llamacpp": {
      "npm": "@ai-sdk/openai-compatible",
      "options": {
        "baseURL": "https://your-llamacpp-endpoint/v1"
      },
      "models": {
        "your-model": {
          "name": "your-model-name",
          "limit": {
            "context": 256000,
            "output": 65536
          }
        }
      }
    }
  }
}
```

Ensure your AI endpoint is accessible and properly secured.

## Output Format

The audit generates a `scanbox.json` file with an array of vulnerabilities. Each entry includes:

- `id`: Unique identifier (e.g., "CRITICAL-1")
- `title`: Brief description of the vulnerability
- `type`: Vulnerability type (e.g., "RCE", "SQLi")
- `cvss`: CVSS score
- `file`: Path to the affected file
- `line`: Line number
- `description`: Detailed explanation
- `code`: Minimal code snippet

If no vulnerabilities are found, the file contains an empty array `[]`.

## Examples

See the `test/` directory for sample vulnerable code and expected output.
Testing has been done with remote llama.cpp + gpt-oss:20b.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request. Ensure your changes are tested and update documentation.

## Credits
[![Sansec Logo](https://sansec.io/assets/images/logo.svg)](https://sansec.io)

The prompt was based on [this Sansec article](https://sansec.io/research/claude-finds-353-zero-days-packagist).

## License

This project is licensed under the MIT License. See `LICENSE` for details.

## Disclaimer

We are not responsible for potential dataleaks when using this tool. Always verify your supplier and verify if OpenCode does not read your `config files`. If you find that the `.gitignore` needs some tweaking (OpenCode respects it), please submit a PR.

This tool is for educational and security auditing purposes. Always verify results manually and do not rely solely on automated tools for production security assessments.
