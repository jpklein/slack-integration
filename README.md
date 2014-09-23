### Rally Bot
Pushes notifications from Rally and allows users to fetch ticket details. Notifications are sent whenever:

1. a comment is added to a ticket
2. a new defect, user story, or test case is created
3. a user story changes state

The bot uses a combination of Rally's _state_ and _ready_ fields to track the progress of user stories. When a story's state is set to "In-Progress" and the ready field is checked, rallybot will announce that the story is ready for testing. When the QA team has completed testing, they may either: 1) uncheck the ready flag to have rallybot announce that the story needs work, or 2) set the story to "Completed" to notify the Product Owner that it is ready for acceptance.

> **Note**: Rally automatically sets a story's state to "Completed" when all of its tasks are completed, so be sure to leave at least one open task in order to correctly track stories with defects.
