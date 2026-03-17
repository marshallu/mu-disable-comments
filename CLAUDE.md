# MU Disable Comments

A lean, internal plugin that disables all comments sitewide for Marshall University's WordPress network.

- **Package**: `marshallu/mu-disable-comments`
- **Type**: WordPress plugin
- **Author**: Christopher McComas

## Versioning

When bumping the version:
1. Update the `Version:` header in `disable-comments.php`
2. Create and push a matching git tag so Packagist picks it up: `git tag vX.X.X && git push origin vX.X.X`

## Development Commands

```bash
# Install dependencies
composer install

# Run PHP CodeSniffer (lint)
./vendor/bin/phpcs --standard=WordPress .

# Auto-fix coding standards violations
./vendor/bin/phpcbf --standard=WordPress .

# Run static analysis
./vendor/bin/phpstan analyse
```

## WordPress Coding Standards

This project uses [WordPress Coding Standards (WPCS)](https://github.com/WordPress/WordPress-Coding-Standards) enforced via PHP_CodeSniffer. All code must pass WPCS linting before being considered complete.

### Key rules to follow

- Use tabs for indentation, not spaces
- Use single quotes for strings unless interpolation is needed
- Prefix all functions, classes, hooks, and globals with `mu_disable_comments_` to avoid namespace collisions
- Use `snake_case` for functions and variables; `PascalCase` for class names
- Always escape output (`esc_html()`, `esc_url()`, `esc_attr()`, etc.)
- Hook into WordPress actions/filters rather than executing logic at the top level of files
- Never use `extract()`, `eval()`, or short PHP open tags (`<?`)

### File naming

- PHP files: `class-{name}.php` for class files (all lowercase, hyphen-separated)
- The main plugin file is `disable-comments.php`

## Project Structure

```
mu-disable-comments/
├── disable-comments.php                        # Plugin entry point (header, bootstrap)
├── includes/
│   ├── class-mu-disable-comments.php           # Core singleton class
│   ├── class-mu-disable-comments-admin.php     # Admin/dashboard suppression
│   └── class-mu-disable-comments-public.php    # Front-end, feeds, REST API, XML-RPC
├── composer.json
├── phpcs.xml
├── phpstan.neon
└── .gitignore
```

## What This Plugin Does

**Admin side** (`class-mu-disable-comments-admin.php`):
- Removes the Comments top-level menu page
- Redirects direct access to `edit-comments.php`, `comment.php`, and `options-discussion.php`
- Removes the Recent Comments dashboard widget
- Removes `commentsdiv` and `commentstatusdiv` meta boxes from all post editors
- Strips `comments` and `trackbacks` support from all registered post types
- Removes the Comments node from the admin bar
- Removes the comments column from post list tables

**Public side** (`class-mu-disable-comments-public.php`):
- Filters `comments_open` and `pings_open` to always return false
- Filters `comments_array` to always return empty (hides existing comments)
- Redirects comment feed requests to the home page
- Removes `/wp/v2/comments` endpoints from the REST API
- Removes `pingback.ping` and `pingback.extensions.getPingbacks` from XML-RPC
