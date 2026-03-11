# MU Disable Comments

A lean, internal plugin that disables all comments sitewide for Marshall University's WordPress network.

- **Package:** `marshallu/mu-disable-comments`
- **Type:** WordPress MU (must-use) plugin

---

## What It Disables

- New comments and pings on all post types
- Existing comments rendered on the front end
- Comment feeds
- Comment endpoints in the REST API
- Pingback methods in XML-RPC
- Comments top-level menu in the admin
- Discussion settings page
- Recent Comments dashboard widget
- Comments and Allow Comments meta boxes in the post editor
- Comments column in post list tables
- Comments node in the admin bar
- `comments` and `trackbacks` post type support on all registered post types

---

## Installation

Upload the plugin directory to `wp-content/mu-plugins/` via your mu-plugins loader. No activation step is required — mu-plugins load automatically.

```
wp-content/
└── mu-plugins/
    └── mu-disable-comments/
        ├── disable-comments.php
        └── includes/
            └── ...
```

If your network uses a single-file mu-plugins loader, require the main plugin file from it:

```php
require_once __DIR__ . '/mu-disable-comments/disable-comments.php';
```

---

## How It Works

### Front End

Comment and ping status is overridden at the filter level, regardless of what is saved on individual posts or in WordPress settings. This means no database updates are required — existing post data is left untouched.

| Filter | Behavior |
|---|---|
| `comments_open` | Always returns `false` |
| `pings_open` | Always returns `false` |
| `comments_array` | Always returns an empty array |

Returning an empty array from `comments_array` ensures that themes and templates which iterate over comments render nothing, even if comments exist in the database.

Comment feed URLs (e.g. `/?feed=comments-rss2`, `/?p=1&feed=rss2`) are redirected to the home page via `template_redirect`.

### REST API

The `/wp/v2/comments` collection and single-item endpoints are removed from the registered endpoint list before the API responds.

### XML-RPC

The `pingback.ping` and `pingback.extensions.getPingbacks` methods are unregistered from the XML-RPC server.

### Admin

| Area | Action |
|---|---|
| Comments menu | Removed via `remove_menu_page()` |
| `edit-comments.php` | Redirected to the dashboard |
| `comment.php` | Redirected to the dashboard |
| `options-discussion.php` | Redirected to the dashboard |
| Recent Comments widget | Removed from the dashboard |
| `commentsdiv` meta box | Removed from all post editors |
| `commentstatusdiv` meta box | Removed from all post editors |
| Comments column | Removed from all post list tables |
| Admin bar Comments node | Removed |
| Post type support | `comments` and `trackbacks` removed from all registered post types |

---

## Development

```bash
# Install dev dependencies
composer install

# Check coding standards
./vendor/bin/phpcs --standard=WordPress .

# Auto-fix coding standards violations
./vendor/bin/phpcbf --standard=WordPress .

# Run static analysis
./vendor/bin/phpstan analyse
```

All code follows [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards). Functions, hooks, and globals are prefixed `mu_disable_comments_`.

---

## File Structure

```
mu-disable-comments/
├── disable-comments.php                        # Plugin entry point
├── includes/
│   ├── class-mu-disable-comments.php           # Core singleton, bootstraps all classes
│   ├── class-mu-disable-comments-admin.php     # Admin/dashboard suppression
│   └── class-mu-disable-comments-public.php    # Front-end, feeds, REST API, XML-RPC
└── composer.json
```
