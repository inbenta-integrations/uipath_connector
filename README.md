# UIPath INTEGRATION

### INTRODUCTION
Inbenta can be integrated with UiPath (Robotic Process Automation software) to automate repetitive front office tasks, complex business solutions (such as enterprise resource management), or manufacturing with robotic processes in action.

UiPath streamlines processes, uncovers efficiencies and provides insights, making the path to digital transformation fast and cost-effective. It leverages existing systems to minimize disruption.

### INSTALLATION
UiPath integration with Inbenta is done through webhooks:
* Setup a webhook in the Inbenta chatbot instance.
* This webhook will call the respective Process (in Uipath Orchestrator) by passing the user inputs.
* The process starts the Job in UiPath studio or UiPath assistant.
* Once a Job is done the response from the UiPath is sent back to the chatbot. 

Here is an example of how you could call a uiPath process from Inbenta. Let’s say you want to invoke an Insurance Claim process set up in UiPath.

As a Bot Master you can set up an action (with webhook) in Inbenta and have a form to collect all the information from the user to submit for an Insurance claim. Once the user inputs the values, they will get stored in variables and then Inbenta chatbot will pass the values to the webhook that in turn will invoke the relevant UiPath process. The UiPath process will get executed and the response (in this example a ClaimID) will be sent back to the chatbot.