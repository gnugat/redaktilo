# How to contribute

Everybody should be able to help. Here's how you can make this project more
awesome:

1. [Fork it](https://github.com/gnugat/redaktilo/fork_select)
2. improve it
3. submit a [pull request](https://help.github.com/articles/creating-a-pull-request)

Your work will then be reviewed as soon as possible (suggestions about some
changes, improvements or alternatives may be given).

Don't forget to add tests, make sure that they all pass and to fix the
[coding standards](CONTRIBUTING.md#coding-standards)

## Help with Git

Once the repository is forked, you should track the upstream (original) one
using the following command:

    git remote add upstream https://github.com/gnugat/redaktilo.git

Then you should create your own branch, following the
[branch naming policy](VERSIONING.md#branch-naming):

    git checkout -b <prefix>/<micro-title>-<issue-number>

Once your changes are done (`git commit -am '<descriptive-message>'`), get the
upstream changes:

    git checkout master
    git pull --rebase origin master
    git pull --rebase upstream master
    git checkout <your-branch>
    git rebase master

Finally, publish your changes:

    git push -f origin <your-branch>

You should be now ready to make a pull request.

## Commit messages

The cleaner the git history is, the better.

Git messages should always have the micro title as a tag, written in CamelCase.
The message itself should begin with a verb written in the past tense and
should describe one action, because commits should be atomic (one action = one
commit).

For example, the branch `fix/negative-total-6` could have the commit
`[NegativeTotal] Made the value absolute`.
