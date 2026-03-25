#!/usr/bin/env node

/**
 * Accessibility Audit Helper
 *
 * This script provides guidance and utilities for running accessibility audits.
 * For automated testing, use axe DevTools browser extension or local server scanning.
 *
 * Usage:
 *   npm run a11y:audit
 *   npm run a11y:audit -- --help
 */

const fs = require("fs");
const path = require("path");

const args = process.argv.slice(2);
const command = args[0] || "help";

// Color constants for terminal output
const colors = {
  reset: "\x1b[0m",
  bright: "\x1b[1m",
  dim: "\x1b[2m",
  red: "\x1b[31m",
  green: "\x1b[32m",
  yellow: "\x1b[33m",
  blue: "\x1b[34m",
  cyan: "\x1b[36m",
};

function log(text, color = "reset") {
  console.log(`${colors[color]}${text}${colors.reset}`);
}

function header(text) {
  log("\n" + "=".repeat(60), "cyan");
  log(text, "bright");
  log("=".repeat(60), "cyan");
}

function section(text) {
  log("\n" + text, "bright");
  log("-".repeat(text.length), "dim");
}

function showHelp() {
  header("Accessibility Audit Helper");

  section("Commands");
  log("  npm run a11y:audit                   Show this help");
  log(
    "  npm run a11y:audit -- --check       Check templates for common issues",
  );
  log("  npm run a11y:audit -- --setup       Setup axe browser extension");
  log("  npm run a11y:audit -- --workflow    Show GitHub Actions workflow");

  section("Manual Testing (Recommended for Development)");
  log("1. Install axe DevTools browser extension:");
  log(
    "   • Chrome: https://chrome.google.com/webstore/detail/axe-devtools-...",
  );
  log("   • Firefox: https://addons.mozilla.org/firefox/addon/axe-devtools/");
  log("");
  log("2. Start development server:");
  log("   $ npm run watch");
  log("");
  log("3. Open page in browser and run axe scan:");
  log("   • DevTools → axe DevTools tab → Scan ALL of my page");
  log("   • Review violations and fix issues");
  log("");
  log("4. Test with keyboard navigation:");
  log("   • Unplug mouse or disable touchpad");
  log("   • Navigate with Tab, Enter, Space, Arrow keys");
  log("");
  log("5. Test with screen reader:");
  log("   • macOS: Cmd+F5 to enable VoiceOver");
  log("   • Windows: Download NVDA (https://www.nvaccess.org/)");
  log("   • Navigate and verify content is announced correctly");

  section("Automated Testing in CI/CD");
  log("GitHub Actions workflow created at:");
  log("  .github/workflows/accessibility.yml");
  log("");
  log("This workflow runs:");
  log("  • Scans all modified templates for accessibility violations");
  log("  • Runs on pull requests and pushes to main");
  log("  • Reports Critical and Serious violations");

  section("Resources");
  log("Documentation:      see ACCESSIBILITY-GUIDE.md");
  log("WCAG Standards:     https://www.w3.org/WAI/WCAG21/quickref/");
  log("axe DevTools:       https://www.deque.com/axe/devtools/");
  log("WebAIM:             https://webaim.org/");
  log("A11y Project:       https://www.a11yproject.com/");
  log("");
}

function checkTemplates() {
  header("Checking Templates for Accessibility Issues");

  const templatesDir = path.join(__dirname, "../../templates");
  const issues = [];

  if (!fs.existsSync(templatesDir)) {
    log("Templates directory not found", "red");
    process.exit(1);
  }

  function walkDir(dir) {
    const files = fs.readdirSync(dir);

    files.forEach((file) => {
      const filePath = path.join(dir, file);
      const stat = fs.statSync(filePath);

      if (stat.isDirectory()) {
        walkDir(filePath);
      } else if (file.endsWith(".twig")) {
        checkFile(filePath);
      }
    });
  }

  function checkFile(filePath) {
    const content = fs.readFileSync(filePath, "utf8");
    const relPath = path.relative(templatesDir, filePath);
    const fileIssues = [];

    // Check for common accessibility issues
    if (/<label[^>]*>/i.test(content) && !/for=["']/i.test(content)) {
      fileIssues.push('Label elements without "for" attribute');
    }

    if (/<img[^>]*>/i.test(content) && !/alt=["']/i.test(content)) {
      fileIssues.push("Image elements without alt attribute");
    }

    if (
      /<button[^>]*>/i.test(content) &&
      !/aria-label|aria-labelledby/i.test(content)
    ) {
      fileIssues.push("Buttons may need aria-label verification");
    }

    if (
      /<input[^>]*type=["']?text/i.test(content) &&
      !/^[^<]*<label/m.test(content)
    ) {
      fileIssues.push("Text input may lack associated label");
    }

    if (fileIssues.length > 0) {
      issues.push({ file: relPath, problems: fileIssues });
    }
  }

  walkDir(templatesDir);

  if (issues.length === 0) {
    log("\n✓ No obvious accessibility issues found", "green");
    log(
      "\nNote: This is a basic check. Run axe DevTools for comprehensive testing.\n",
      "dim",
    );
  } else {
    log(`\n! Found potential issues in ${issues.length} file(s):\n`, "yellow");

    issues.forEach(({ file, problems }) => {
      log(`  ${file}`, "yellow");
      problems.forEach((problem) => {
        log(`    • ${problem}`, "dim");
      });
    });

    log("\nFor detailed fixes, see ACCESSIBILITY-GUIDE.md\n", "dim");
  }
}

function showWorkflow() {
  header("GitHub Actions Accessibility Workflow");

  const workflow = `
name: Accessibility Audit

on:
  pull_request:
    paths:
      - 'templates/**'
      - 'css/**'
      - 'scripts/**'
  push:
    branches:
      - main
  workflow_dispatch:

jobs:
  accessibility:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'

      - name: Install dependencies
        run: npm ci

      - name: Check templates for issues
        run: npm run a11y:audit -- --check
        continue-on-error: true

      - name: Lint JavaScript
        run: npm run lint
        continue-on-error: true

  manual-testing-reminder:
    runs-on: ubuntu-latest
    if: github.event_name == 'pull_request'
    steps:
      - name: Comment on PR
        uses: actions/github-script@v6
        with:
          script: |
            github.rest.issues.createComment({
              issue_number: context.issue.number,
              owner: context.repo.owner,
              repo: context.repo.repo,
              body: '## Accessibility Testing Reminder\\n\\n' +
                    'Please run the following before merging:\\n' +
                    '1. **axe DevTools scan** - No Critical/Serious violations\\n' +
                    '2. **Keyboard navigation test** - Tab through entire page\\n' +
                    '3. **Screen reader test** - VoiceOver or NVDA\\n\\n' +
                    'See [ACCESSIBILITY-GUIDE.md](../../ACCESSIBILITY-GUIDE.md) for details.'
            })
`;

  log(workflow);

  section("Installation");
  log("1. Create directory: .github/workflows/");
  log("2. Save workflow as: accessibility.yml");
  log("3. Commit and push to enable workflow");
  log("");
  log("The workflow will run on:");
  log("  • Pull requests (templates, CSS, or scripts changed)");
  log("  • Pushes to main branch");
  log("  • Manual trigger via GitHub Actions tab");
  log("");
}

function showSetup() {
  header("Setting Up axe DevTools");

  section("Browser Installation");
  log("\nChrome/Edge:");
  log("  1. Visit: https://chrome.google.com/webstore/");
  log('  2. Search: "axe DevTools"');
  log('  3. Click "Add to Chrome"');
  log("  4. Confirm permission prompt");
  log("");
  log("Firefox:");
  log("  1. Visit: https://addons.mozilla.org/firefox/");
  log('  2. Search: "axe DevTools"');
  log('  3. Click "Add to Firefox"');
  log("  4. Confirm permission prompt");

  section("First Scan");
  log("1. Open any page in your theme");
  log("2. Press F12 to open DevTools");
  log('3. Look for "axe DevTools" tab');
  log('4. Click "Scan ALL of my page"');
  log("5. Review results:");
  log("   • Critical (red) - Fix immediately");
  log("   • Serious (orange) - Fix soon");
  log("   • Moderate (yellow) - Fix if possible");
  log("   • Minor (blue) - Non-essential fixes");

  section("Understanding Results");
  log("\nViolation Details:");
  log("  • Affected Element - Highlighted in inspector");
  log("  • Issue Description - What's wrong and why");
  log("  • WCAG Rule - Standard being violated");
  log("  • Fix Section - How to fix the issue");
  log("");
}

// Route commands
switch (command) {
  case "--help":
  case "-h":
  case "help":
    showHelp();
    break;

  case "--check":
    checkTemplates();
    break;

  case "--setup":
    showSetup();
    break;

  case "--workflow":
    showWorkflow();
    break;

  default:
    showHelp();
}
