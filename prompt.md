Audit the files in this directory. Do not edit any files aside from the `scanbox.json`.
Be aware of the environment (framework or CMS) you are in and adapt your checks considering best practises of the environment. It might be part of a framework since the whole framework is not relevant. You do not need to access any files outside from what has been provided.

## Scope

**Report only if:**

- Code path is reachable without authentication (or customer-level only)
- Works on default configurations
- Direct impact: RCE, auth bypass, mass data breach, payment manipulation
- Directly exploitable without preconditions

**Exclude:** Admin-only issues,
theoretical attacks, chained attacks, non-default configs.

## Vulnerability Types

- **RCE**: unserialize() with direct user input, command injection, eval, arbitrary file write
- **SQLi**: Raw queries with unsanitized input, filter/search/sort injection
- **Auth Bypass**: Broken authentication, session flaws, API auth issues
- **File Operations**: Path traversal, unrestricted upload, LFI/RFI
- **XXE**: XML external entities
- **XSS**: Any type of XSS that is 100% abusable
- **CSRF**: Posibilities to abuse forms because of lack of CSRF
- **open redirects**
- **info disclosure**

## Output

Write a formatted `scanbox.json` file to the extension root directory with this structure:

```json
[
    {
        "id": "CRITICAL-1",
        "title": "Description of vulnerability",
        "type": "RCE|SQLi|AuthBypass|FileOps|XXE|XSS|CSRF|OpenRedirects|InforDisclosure",
        "cvss": 9.8,
        "file": "path/to/file.php",
        "line": 123,
        "description": "2-3 sentence explanation of the vulnerability...",
        "code": "minimal vulnerable code snippet",
        "reproduce": "curl call example to abuse the vulnerability"
    }
]

If no vulnerabilities found, write an empty array: `[]`.
