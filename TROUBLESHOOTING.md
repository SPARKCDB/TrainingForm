# GitHub Pages Deployment Troubleshooting

## Error: "Get Pages site failed"

You're seeing this error because GitHub Pages needs to be enabled manually first.

## ✅ SOLUTION: Enable Pages Manually (30 seconds)

### Step 1: Go to Repository Settings
Visit: https://github.com/SPARKCDB/TrainingForm/settings/pages

### Step 2: Enable GitHub Pages
Under **"Build and deployment"**:
- **Source**: Select **"GitHub Actions"** from the dropdown
- Click **Save**

### Step 3: Re-run the Failed Workflow
1. Go to: https://github.com/SPARKCDB/TrainingForm/actions
2. Click on the failed workflow run
3. Click **"Re-run all jobs"** button (top right)

That's it! The deployment will now succeed.

---

## Alternative: Manual GitHub Pages Enablement via Settings

If the above doesn't work, try this:

1. **Go to Settings**: https://github.com/SPARKCDB/TrainingForm/settings
2. **Scroll to "GitHub Pages" section** (in the left sidebar)
3. **Source**: Change to "GitHub Actions"
4. **Save**
5. **Push updated workflow** (already done)
6. **Wait for automatic deployment** or manually trigger the workflow

---

## What Changed?

The workflow has been updated to include `enablement: true` which tells GitHub Actions to automatically enable Pages. However, for security reasons, the first-time enablement often requires manual confirmation in repository settings.

---

## Expected Timeline

Once Pages is enabled:
- ⏱️ **First deployment**: 2-5 minutes
- ⏱️ **Subsequent deployments**: 1-2 minutes
- 🌐 **Live URL**: https://sparkcdb.github.io/TrainingForm/

---

## Verify Deployment

After enabling Pages:

1. ✅ Check workflow status: https://github.com/SPARKCDB/TrainingForm/actions
2. ✅ Look for green checkmark on "Deploy to GitHub Pages"
3. ✅ Visit your form: https://sparkcdb.github.io/TrainingForm/

---

## Common Issues

### Issue: 404 Not Found after deployment
**Solution**: Wait 2-3 minutes and clear browser cache

### Issue: Workflow still failing
**Solution**: 
- Verify Pages is enabled in Settings
- Check that Source is set to "GitHub Actions"
- Ensure repository is public (or you have GitHub Pro for private repos)

### Issue: Changes not showing
**Solution**: 
- Clear browser cache
- Try incognito/private browsing
- Wait for workflow to complete (check Actions tab)

---

## Manual Deployment (Alternative Method)

If GitHub Actions continues to fail, you can use the `gh-pages` branch method:

1. Create and switch to `gh-pages` branch:
   ```bash
   git checkout -b gh-pages
   ```

2. Push to GitHub:
   ```bash
   git push origin gh-pages
   ```

3. In Settings → Pages:
   - Source: "Deploy from a branch"
   - Branch: `gh-pages`
   - Folder: `/ (root)`

---

## Need More Help?

- 📧 Contact: support@sparkcdb.org
- 📖 Check: README.md and DEPLOYMENT-GUIDE.md
- 🔍 GitHub Actions Logs: Show detailed error messages

---

**Next Step**: Enable GitHub Pages in repository settings, then re-run the workflow!
