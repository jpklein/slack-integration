### What is Rallybot?

Rallybot is part of a set of scripts that aim to make Slack a central stream of communication, so that team members are updated about the project's health without having to dart between different systems. Specifically, it sends announcements to a Slack channel whenever certain events take place in a Rally project. It assumes that you are working on an agile software development project where each user story must pass between engineers, quality assurance, and product owners.

### Why should I use it?

Rally is a fine platform, but by all accounts its notification system is severly lacking. Also, in its effort to include as many different workflows as possible, it is intentionally vague about the steps a software-development story must make between development and final acceptance. Rallybot - and the workflow it embodies - was created to address both these concerns.

### How do I use it?

Once it is [installed](https://github.com/jpklein/slack-integration#installation), the bot polls your Rally project for changes and posts notifications to a channel in Slack. Notifications are sent whenever:

1. a comment is added to a ticket
2. a new defect, user story, or test case is created
3. a user story changes state
 
While the first two events correlate directly to a specific action in Rally, rallybot's notion of states doesn't map as neatly to a single field so that it can better represent the flow of stories between different actors involved in agile software projects.

#### Tracking story completion

Once a user story has been accepted as part of an iteration in Rally, the bot uses a combination of Rally's _state_ and _ready_ fields to track the progress of user stories.  

When a story's state is set to "In-Progress" and the ready field is checked, rallybot will announce that the story is ready for testing.

When the QA team has completed testing, they may either: 1) uncheck the ready flag to have rallybot announce that the story needs work, or 2) set the story to "Completed" to notify the Product Owner that it is ready for acceptance.

> **Note**: Rally automatically sets a story's state to "Completed" when all of its tasks are completed, so be sure to leave at least one open task in order to correctly track stories with defects.
