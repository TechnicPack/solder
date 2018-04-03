# Contributing to TechnicPack

First off, thanks for taking the time to contribute!

The following is a set of guidelines for contributing to TechnicPack and its
packages, which are hosted in the TechnicPack organization on GitHub. These are
mostly guidelines, not rules. Use your best judgment, and feel free to propose
changes to this document in a pull request.

## How Can I Contribute

### Bug Reports

To encourage active collaboration, we strongly encourage pull requests,
not just bug reports. "Bug reports" may also be sent in the form of a pull
request containing a failing test.

However, if you file a bug report, your issue should contain a title and a clear
description of the issue. You should also include as much relevant information
as possible and a code sample that demonstrates the issue. The goal of a bug
report is to make it easy for yourself - and others - to replicate the bug and
develop a fix.

Remember, bug reports are created in the hope that others with the same problem
will be able to collaborate with you on solving it. Do not expect that the bug
report will automatically see any activity or that others will jump to fix it.
Creating a bug report serves to help yourself and others start on the path of
fixing the problem.

### Pull Requests

When you submit pull requests, the project maintainer has to read them and
understand them before they'll be merged in. Misunderstanding requests can lead
to them being more difficult to merge. To help with this, when submitting you
should:

 - Split up large patches into smaller units of functionality.
 - Keep your commit messages relevant to the changes in each individual unit.
 - Ensure that added code is being tested

When writing commit messages please try and stick to the same style as other
commits, namely:

 - A brief one line summary, starting with a capital and with no full stop.
 - A blank line.
 - Full description, as proper sentences with capitals and full stops.

For simple commits the one line summary is often enough and the body of the
commit message can be left out.

### Code review
Once your pull request has been opened it will be assigned to one or more
reviewers. Those reviewers will do a thorough code review, looking for
correctness, bugs, opportunities for improvement, documentation and comments,
and style.

Commit changes made in response to review comments to the same branch on your
fork.

### Squash and Merge
Upon merge (by either you or your reviewer), all commits left on the review
branch should represent meaningful milestones or units of work. Use commits to
add clarity to the development and review process.

Before merging a PR, squash any fix review feedback, typo, merged, and rebased
sorts of commits.

It is not imperative that every commit in a PR compile and pass tests
independently, but it is worth striving for.

In particular, if you happened to have used git merge and have merge commits,
please squash those away: they do not meet the above test.

A nifty way to manage the commits in your PR is to do an [interactive rebase](https://git-scm.com/book/en/v2/Git-Tools-Rewriting-History),
which will let you tell git what to do with every commit:

```bash
$ git fetch upstream
$ git rebase -i upstream/master
```

For mass automated fixups (e.g. StyleCI changes), use one or more commits for
the changes to tooling and a final commit to apply the fixup en masse. This
makes reviews easier.

## Styleguides

## PHP Coding Style

This package follows the PSR-2 coding standard and the PSR-4 autoloading
standard. We use the Laravel preset for style checks at StyleCI. You can see the
full list of fixers in the [StyleCI Docs](https://styleci.readme.io/docs/presets#section-laravel)

### StyleCI

Don't worry if your code styling isn't perfect! StyleCI will automatically flag
any style fixes into the repository after code is pushed to a pull request. This
allows us to focus on the content of the contribution and not the code style.
