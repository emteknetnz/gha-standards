# GitHub Actions - Standards

GitHub Action - Keeps module up to standard doing tasks such as ensuring workflow files are up to date.

## Usage

There are no inputs for configuring this action

**.github/workflows/standards.yml**
```yml
name: Standards
on:
  # Run on a schedule of the 5 day of each month
  schedule:
    - cron: '0 0 5 * *'
  workflow_dispatch:
jobs:
  keepalive:
    name: Standards
    runs-on: ubuntu-latest
    steps:
      - name: Standards
        uses: silverstripe/gha-standards@v1
```
