### What is Rallybot?

Rallybot is part of a set of scripts that aim to make Slack the central stream of communication about projects, so that team members receive status updates without having to constantly dart between different systems. Specifically, it posts announcements to a Slack channel whenever certain events occur in a Rally project. It assumes that you are working on an agile software development project where each user story must pass between engineers, quality assurance, and product owners.

### Why should I use it?

Rally is a fine platform, but by all accounts its notification system is severly lacking. Also, in an effort to include as many different types of projects as possible, it is intentionally vague about the steps a software-development story must make to go from development to final acceptance. Rallybot - and the workflow it embodies - was created to address both these concerns.

### How do I use it?

Once it's [installed](https://github.com/jpklein/slack-integration#installation), the bot polls your Rally project for changes and posts notifications to a channel in Slack. Notifications are sent whenever:

1. a comment is added to a ticket
2. a new defect, user story, or test case is created
3. a user story changes state
 
While the first two events correlate directly to a specific action in Rally, the bot uses a combination of Rally's _state_ and _ready_ fields to better represent the flow of stories between the actors involved in agile software projects.

#### Tracking story completion

A common question that arises when software teams first use Rally is: "how do I know when a story is ready for testing?" Rally provides 5 states for a user story - Undefined, Defined, In-Progress, Completed, and Accepted - but these don't map neatly to the different stages that a piece of software must undergo to reach acceptance. Rallybot alleviates this confusion by notifying team members whenever a handoff occurs, either: from a developer to a test engineer (or vice-versa), or from a test engineer to the product owner.

##### Developers

Once a user story has been accepted as part of an iteration and you have added tasks to it, Rally should set the story's state to In-Progress and its ready flag should be unchecked. When your code is complete and in a place that it can be tested (i.e. deployed to a QA environment), **check the ready flag** on the related user story in Rally.  Rallybot will post an announcement that the story is "ready for QA" in our Slack channel along with a link. That should be the only signal the QA team needs to begin testing. 

If QA finds any defects, they will use rallybot to post an announcement that the story "needs work" and the ticket will be returned to you in the same state as you first started working on it: In-Progress with the ready flag unchecked. After you've closed the defect tickets in Rally and deployed the fix to a QA environment, remember to check the user story's ready flag once more to tell the team that it is ready for review.

##### QA Team

When a user story is added to an iteration, create at least one child task to account for the time you'll spend testing it. Rally has a nasty habit of changing a story's state to Completed when all of its tasks have zero hours left to do, so maintaining these QA tasks is essential to having rallybot report the correct status.

Once a developer has finished work on a story and deployed it to a test environment, rallybot will post a message in our Slack channel that it is "ready for QA". That's your cue to begin your testing and/or add any questions you have to the story's discussion tab; Rallybot will automatically repost your comments to Slack for everyone to see. You can even include other team members' Slack login names in your comments to notify them directly.

If a story doesn't pass testing, create tickets for any defects you find and make sure to task out the hours you think you'll spend retesting the feature. After this is done, **uncheck the ready flag** of the user story in Rally. Rallybot wiil post a message to Slack that the story "needs work" and a developer should begin working on the fix. Look for another "ready for QA" announcement in Slack for the story to be retested.

When a story passes testing - or all of its defects have been closed - **set the state to Completed**. This will trigger a notification that the story is "ready for acceptance" that should catch the attention of our product owner. You can do this either by manually changing the story's state field, or by setting the to do field of all its child tasks to zero (as mentioned above).

##### Product Owner

You will see many messages posted to our Slack channel by rallybot over the course of an iteration, but be on the lookout for reports of user stories "ready for acceptance".  These are your cue that a certain feature is ready to be evaluated and the developers await your feedback. Feel free to add your thoughts to the story's discussion tab in Rally; they will be reposted to Slack for everyone to see.

### How can I help?

Rallybot is a work in progress open to your comments and contributions. Feel free to reach out to the project on github.
