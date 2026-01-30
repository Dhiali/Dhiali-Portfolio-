# Quick Fix Guide for Common Git Issues

## Current Issue: Push Rejected and Unwanted Files

If you've encountered this error when trying to push:
```
! [rejected]        main -> main (non-fast-forward)
error: failed to push some refs to 'https://github.com/Dhiali/Dhiali-Portfolio-.git'
hint: Updates were rejected because the tip of your current branch is behind
hint: its remote counterpart.
```

Additionally, if you've committed `node_modules/` and `dist/` files which should not be tracked.

## Quick Fix (Step by Step)

### Step 1: Remove node_modules and dist from Your Last Commit

⚠️ **IMPORTANT**: First ensure .gitignore is properly configured (it should be with recent changes).

If you just committed build artifacts, undo that commit:

```bash
# Undo the last commit but keep the files
git reset --soft HEAD~1

# Remove node_modules and dist from git tracking
git rm -r --cached node_modules/ dist/ 2>/dev/null || true

# Check what files are now staged - verify node_modules and dist are NOT included
git status

# Selectively add only the files you want to commit
git add src/
git add package.json
git add README.md
# Add other specific files as needed - DON'T use "git add ."

# Commit only the files you actually want
git commit -m "Your actual changes (without build artifacts)"
```

### Step 2: Sync with Remote Branch

Now your local branch is likely behind the remote. Fix it:

```bash
# Fetch latest changes from remote
git fetch origin

# Merge remote changes into your local branch
git pull origin main

# Resolve any conflicts if they appear
# (Git will guide you if there are conflicts)

# Now push your changes
git push origin main
```

### Step 3: Verify Everything is Clean

```bash
# Run the cleanup script
bash cleanup-git.sh

# Should show:
# ✓ No node_modules or dist files tracked
# ✓ .gitignore properly configured
# ✓ Branch is up to date
```

## Alternative: Start Fresh (If Above Doesn't Work)

⚠️ **WARNING**: This will permanently discard all uncommitted local changes!

If the above steps are too complicated or something goes wrong:

```bash
# 1. IMPORTANT: Backup any uncommitted changes
#    Copy any modified files you care about to a safe location outside the repo

# 2. Reset to match remote (DESTRUCTIVE - discards all local changes)
git fetch origin
git reset --hard origin/main

# 3. If you had changes to reapply, make them again

# 4. Stage ONLY the files you need (be specific!)
git add src/components/MyComponent.jsx
git add package.json
# etc. - DON'T use "git add ."

# 5. Commit and push
git commit -m "Your changes"
git push origin main
```

## Prevention for Next Time

The following files have been added to prevent this issue in the future:

1. **`.gitattributes`** - Handles line endings automatically (fixes LF/CRLF warnings)
2. **Enhanced `.gitignore`** - More comprehensive patterns to ignore build artifacts
3. **`cleanup-git.sh`** - Helper script to check repository health
4. **`GIT_ISSUES_FIX.md`** - Comprehensive troubleshooting guide

### Best Practice Workflow

Instead of `git add .`, be specific:

```bash
# Good - specific files
git add src/
git add package.json
git add README.md

# Bad - adds everything including build artifacts
git add .
```

Always check before committing:

```bash
git status
git diff --cached
```

## Understanding the Error

### "non-fast-forward" means:
- Your local `main` branch and remote `main` branch have diverged
- Remote has commits that you don't have locally
- Git refuses to overwrite remote commits

### The solution:
- Pull remote changes first (`git pull`)
- Merge them with your local changes
- Then push

### Never force push unless you're absolutely sure:
```bash
# ⚠️ DON'T DO THIS unless you know what you're doing
git push --force origin main
# This will delete remote commits!
```

## Need More Help?

1. Read `GIT_ISSUES_FIX.md` for detailed explanations
2. Run `bash cleanup-git.sh` to check repository status
3. Check the git log: `git log --oneline --all --graph -10`
4. Check remote status: `git fetch origin && git status`

## Quick Reference

```bash
# See what you're about to commit
git diff --cached

# Undo last commit (keep changes)
git reset --soft HEAD~1

# Undo last commit (discard changes)
git reset --hard HEAD~1

# Remove from staging
git reset HEAD <file>

# Check what's being ignored
git check-ignore -v node_modules/ dist/

# Sync with remote
git fetch origin
git pull origin main
git push origin main
```

## You're All Set! 🎉

After following these steps:
- ✅ node_modules and dist won't be committed anymore
- ✅ Line endings will be handled automatically
- ✅ You'll be synced with the remote repository
- ✅ You'll have tools to prevent and fix issues

Remember: **Always run `git status` and review changes before committing!**
