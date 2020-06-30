# Create a new Laravel project that will have the following functionality.

## Page 1
#### Invoices grid + "Add invoice" button

Add button will open a dialog with the following:
- School name - text field
- Description - text area
- email - email
- amount

Create and Cancel buttons.

The invoice will be sent to the person in the provided email with link for payment when clicked on create.

#### Grid Columns:
- School name 
- Description 
- Amount 
- Payment status (Invoice sent / payment received)
- invoice link (can be button that generates it or just link that can be copied)


## Page 2
This will be the page for user to pay the invoice
It will contain the school, description, amount and button to pay.

Pay button will redirect to payment system page (where the person will submit the payment and upon completion will be redirected back - this is part of payment system, not part of our project)

##Page 3

Transaction history
This page will contain history of all the payments done and their main details (like invoice id,payer, itransaction id) and a button to open a dialog with all the details received from payment system

#The description above is the final functionality  (it will contain in addition integration with the system)

As part of test task.
- Page 1 without the email sending.
- Page 2 without redirect(pay button will be non functional)
- Page 3 - no need

I do want to see the DB structure and classes that will be used to work with payment system.
If it is more convenient, you can connect any existing system.
