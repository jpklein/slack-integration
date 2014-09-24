### What is Rallybot?

Rallybot is part of a set of scripts that aim to make Slack a central stream of communication, so that team members are updated about the project's health without having to dart between different systems. Specifically, it sends announcements to a Slack channel whenever certain events take place in a Rally project. It assumes that you are working on an agile software development project where each user story must pass between engineers, quality assurance, and product owners.

### Why should I use it?

Rally is a fine platform, but by all accounts its notification system is severly lacking. Also, in its effort to include as many different workflows as possible, it is intentionally vague about the steps a software-development story must make between development and final acceptance. Rallybot - and the workflow it embodies - was created to address both these concerns.

### How do I use it?

Once it's [installed](https://github.com/jpklein/slack-integration#installation), the bot polls your Rally project for changes and posts notifications to a channel in Slack. Notifications are sent whenever:

1. a comment is added to a ticket
2. a new defect, user story, or test case is created
3. a user story changes state
 
While the first two events correlate directly to a specific action in Rally, the bot uses a combination of Rally's _state_ and _ready_ fields to better represent the flow of stories between the different actors involved in agile software projects.

#### Tracking story completion

A common question that arises when software teams first use Rally is: "how do I know when a story is ready to be tested?" Rally provides 5 states for a user story (Undefined, Defined, In-Progress, Completed, and Accepted) but these don't map neatly to the stages of development that a piece of software must undergo to reach acceptance. Rallybot alleviates this confusion by notifying team members when a handoff has occured, either: from a developer to a test engineer (or vice-versa), or from a test engineer to the product owner.

##### Developers

Once a user story has been accepted as part of an iteration and you have added tasks to it, Rally should set the story's state to In-Progress and its ready flag should be unchecked.

When you have finished writing the code described by the user story and when that code is in a place that it can be tested (ie. on a QA environment rather than your localhost), simply **check the ready flag** on the user story in Rally.  This will trigger an announcement that the story is "ready for QA" and post a link in our Slack channel.

As a result of their testing, QA may return the story to you with defects. The user-story ticket should be in the same state as when you first started working on it (ie. In-Progress with the ready flag unchecked). After you've corrected those defects, closed their associated tickets, and deployed the fix to a QA environment, be sure to check the user story's ready flag once more to tell the team that it is ready for review.

##### QA Team

When a user story is added to an iteration, create at least one child task to account for the time you'll spend testing it. Rally has a nasty habit of automatically setting a story's state to Completed when all of its tasks are completed. So maintaining these QA tasks is essential to having rallybot report the correct information in Slack.

Once a developer has finished work on a story and deployed it to a test environment, rallybot will post a message in our Slack channel that it is "ready for QA". That is your signal to begin your testing and/or add any questions you have to the story's discussion tab - rallybot will repost any comments you make to Slack for everyone to see. You can even include other team members' Slack login name in your comments to notify them directly.

After you have completed testing, you may either: 1) uncheck the ready flag to have rallybot announce that the story needs work, or 2) set the story to "Completed" to notify the Product Owner that it is ready for acceptance.

> **Note**: Rally automatically sets a story's state to "Completed" when all of its tasks are completed, so be sure to leave at least one open task in order to correctly track stories with defects.

##### Product Owner
