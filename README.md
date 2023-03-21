> **Warning**  
> Just for trying out the OpenAI API. Don't use in production.

# gitcm-ai

Very basic rewrite of [Nutlope/aicommits](https://github.com/Nutlope/aicommits).

## Setup

Install dependencies:
```sh
composer install
```

Build a single file executable:
```sh
php gitcm-ai app:build
```

The compiled executable is now at `builds/gitcm-ai`.

## Usage

```sh
gitcm-ai generate-message
```

This command prints a commit message based on your staged changes.
