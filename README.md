# Custom Drupal Modules

This repository contains two custom Drupal modules:

1. **Hello World** – A simple example module with a page and a block.
2. **Anytown** – A placeholder module.

---

## Modules Overview

### Hello World Module

**Type:** Custom module


The `hello_world` module is a simple module demonstrating:

- A custom page at `/hello-world`
- A reusable block plugin

This module is intended for learning purposes only.

---

**Installation**

1. Copy the module folder to your Drupal project:

```bash
  web/modules/custom/hello_world
```

2. Enable the module:

```bash
  drush en hello_world
  drush cr
```

3. Enable the module:
```bash
   drush en hello_world
  ```
---

**Features**
1. Custom Page

- URL: `/hello-world`
- Displays: "Hello world!"
- Implemented using a controller

2. Custom Block

- Block name: **Hello block**
- Category: **Custom**
- Displays: "Hello world block!"
- Can be placed in any theme region

---

**Usage**

- Visit ```/hello-world```

- Place the block

  1. Go to **Structure → Block layout**
  2. Click **Place block**
  3. Search for **Hello block**
  4. Choose a region and save

---

### Anytown Module

**Type:** Custom module

**Installation:**

1. Copy the module folder to your Drupal project:

```bash
  web/modules/custom/anytown
```

2. Enable the module:

```bash
  drush en anytown
  drush cr
```

**Utility:**
Currently this module serves as a placeholder and does not contain any functionality.


---
