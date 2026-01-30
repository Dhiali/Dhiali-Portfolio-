# Git Issues Fix Guide

This guide helps resolve common git issues with the portfolio repository.

## Problem 1: Accidentally Committed node_modules/ and dist/ Files

If you've committed `node_modules/` or `dist/` files, follow these steps to clean them up:

### Option A: Remove from Last Commit (if not pushed yet)

```bash
# Reset the last commit but keep changes
git reset --soft HEAD~1

# Remove tracked files from staging
git rm -r --cached node_modules/ dist/

# Now commit only the files you want
git add .
git commit -m "Your changes (without node_modules and dist)"
```

### Option B: Remove from Git History (if already pushed)

```bash
# Remove node_modules and dist from git tracking
git rm -r --cached node_modules/ dist/

# Verify they're removed from staging
git status

# Add .gitignore to ensure it's tracked
git add .gitignore

# Commit the removal
git commit -m "Remove node_modules and dist from git tracking"

# Push the changes
git push origin main
```

**Note:** The `.gitignore` file has been updated to prevent this from happening again.

## Problem 2: Branch Behind Remote (Non-Fast-Forward Error)

When you see:
```
! [rejected]        main -> main (non-fast-forward)
error: failed to push some refs
```

This means your local branch is behind the remote branch. Here's how to fix it:

### Step 1: Fetch Remote Changes

```bash
git fetch origin
```

### Step 2: Check the Difference

```bash
# See what commits are on remote but not local
git log HEAD..origin/main --oneline

# See what commits are on local but not remote
git log origin/main..HEAD --oneline
```

### Step 3: Choose Your Strategy

#### Option A: Merge Remote Changes (Recommended)

```bash
# Merge remote changes into your local branch
git pull origin main

# Resolve any conflicts if they occur
# Then push your changes
git push origin main
```

#### Option B: Rebase Your Changes

```bash
# Rebase your local commits on top of remote
git pull --rebase origin main

# Resolve any conflicts if they occur
# Then push your changes
git push origin main
```

#### Option C: Force Push (⚠️ Use with Caution)

**WARNING:** This will overwrite remote history. Only use if you're sure you want to discard remote changes.

```bash
git push --force origin main
```

## Problem 3: LF/CRLF Line Ending Warnings

Windows users may see warnings like:
```
warning: in the working copy of 'file.js', LF will be replaced by CRLF
```

This has been fixed by adding a `.gitattributes` file that enforces consistent line endings.

### One-Time Git Configuration

Run these commands to configure git globally for line endings:

```bash
# Configure git to handle line endings automatically
git config --global core.autocrlf true

# On Windows, this converts LF to CRLF on checkout and CRLF to LF on commit
```

### Refresh Line Endings in Repository

⚠️ **WARNING**: The following commands will discard uncommitted changes. Back up your work first!

If you want to normalize all line endings in your repository:

```bash
# IMPORTANT: Commit or backup any uncommitted changes first!

# Remove all files from git's index (but keep them in working directory)
git rm --cached -r .

# Re-add all files (git will normalize line endings based on .gitattributes)
git add .

# Commit the normalized files
git commit -m "Normalize line endings"
```

## Best Practices

### Before Committing

Always check what you're about to commit:

```bash
# See which files are staged
git status

# See the actual changes
git diff --cached
```

### Safe Workflow

```bash
# 1. Check current status
git status

# 2. Stage only specific files you want (be selective!)
git add src/components/
git add package.json
git add README.md
# DON'T use "git add ." - it may add unwanted files

# 3. Review what's staged
git status
git diff --cached

# 4. Commit
git commit -m "Descriptive message"

# 5. Pull before pushing
git pull origin main

# 6. Push
git push origin main
```

## Verification

After applying fixes, verify:

```bash
# 1. Check that node_modules and dist are not tracked
git ls-files | grep -E "^(node_modules|dist)/"
# Should return nothing

# 2. Check .gitignore is working
git check-ignore node_modules/
git check-ignore dist/
# Both should output the path, confirming they're ignored

# 3. Check line endings are configured
git config core.autocrlf
# Should return "true" on Windows
```

## Getting Help

If you encounter issues:

1. Check the status: `git status`
2. Check the log: `git log --oneline -10`
3. Check remote state: `git fetch origin && git status`
4. Review `.gitignore` and `.gitattributes` files

## Quick Reference

```bash
# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1

# Remove file from staging
git reset HEAD <file>

# Discard local changes to a file
git checkout -- <file>

# Update .gitignore and remove cached files
git rm -r --cached .
git add .
git commit -m "Update .gitignore"

# See what would be pushed
git log origin/main..HEAD

# See what would be pulled
git log HEAD..origin/main
```
