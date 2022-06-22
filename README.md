# GitHub Actions - Standards

GitHub Action - Keeps module up to standard doing tasks such as ensuring workflow files are up to date.

# Superceeded

[module-standardiser](https://github.com/emteknetnz/module-standardiser)

# This will not work for updating workflow files

```
! [remote rejected] pulls/1/standards-1655812934 -> pulls/1/standards-1655812934 (refusing to allow a GitHub App to
create or update workflow `.github/workflows/ci.yml` without `workflows` permission)
```
We need to use a PAT (personal access token) with the 'workflow - Update GitHub Action workflows ' checkbox
ticked, and then add this token to every repo. We do not want to have to use PATs on every repo, so this solution
does not seem appropriate

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
