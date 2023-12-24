-- Começar por criar os seguintes utilizadores (antes de popular)
-- ('Admin Account', 'admin', 'password123', 'admin@example.com'); -> Admin
-- ('Jane Smith', 'janesmith', 'password123', 'janesmith@example.com'); -> Agent
-- ('Mark Johnson', 'markjohnson', 'password123', 'markjohnson@example.com'); -> Client
-- ('Sarah Wilson', 'sarahwilson', 'password123', 'sarahwilson@example.com'); -> Client

-- Insert agents
INSERT INTO Agent(user_id, client_id) VALUES (1, 1);
INSERT INTO Agent(user_id, client_id) VALUES (2, 2);

-- Insert admins
INSERT INTO Admin(user_id, agent_id) VALUES (1, 1);

-- Insert sample departments
INSERT INTO Department (name) VALUES ('Sales');
INSERT INTO Department (name) VALUES ('Support');
INSERT INTO Department (name) VALUES ('Development');

-- Insert sample AgentDepartment
INSERT INTO AgentDepartment(agent_id, department_id) VALUES (2, 1);
INSERT INTO AgentDepartment(agent_id, department_id) VALUES (2, 2);

-- Insert sample statuses
INSERT INTO Status (name) VALUES ('Open - Waiting for Answer');
INSERT INTO Status (name) VALUES ('Open - Support Replied');
INSERT INTO Status (name) VALUES ('Closed - Solved');
INSERT INTO Status (name) VALUES ('Closed - Unsolved');

-- Insert sample hashtags
INSERT INTO Hashtag(id, name) VALUES(1, '#helpme');
INSERT INTO Hashtag(id, name) VALUES(2, '#HQ1');
INSERT INTO Hashtag(id, name) VALUES(3, '#LOL');

-- Insert sample TicketHashtag
INSERT INTO TicketHashtag(ticket_id, hashtag_id) VALUES (1, 1);
INSERT INTO TicketHashtag(ticket_id, hashtag_id) VALUES (1, 2);
INSERT INTO TicketHashtag(ticket_id, hashtag_id) VALUES (2, 3);


-- Insert sample Questions
INSERT INTO Question (question, answer)
VALUES ("How do I reset my password?", "To reset your password, please visit our website and navigate to the 'Account Settings' page. From there, you'll find an option to reset your password. Follow the instructions provided to create a new password.");

INSERT INTO Question (question, answer)
VALUES ("What payment methods do you accept?", "We accept a wide range of payment methods, including credit cards, debit cards, PayPal, and mobile payment apps. You can choose the payment method that is most convenient for you during the checkout process.");

INSERT INTO Question (question, answer)
VALUES ("Can I track my order?", "Absolutely! Once your order is shipped, you will receive a confirmation email with a tracking number. Simply click on the tracking number or visit our website and enter the tracking number in the designated field to track the status and location of your order.");

INSERT INTO Question (question, answer)
VALUES ("How can I contact customer support?", "Our customer support team is available 24/7 to assist you. You can reach us through our toll-free hotline, email support, or live chat on our website. We're here to help and answer any questions or concerns you may have.");

INSERT INTO Question (question, answer)
VALUES ("Is there a warranty for your products?", "Yes, all our products come with a standard warranty. The duration and coverage of the warranty may vary depending on the product. Please refer to the product description or contact our customer support for specific warranty information.");

INSERT INTO Question (question, answer)
VALUES ("How do I return an item?", "If you wish to return an item, please visit our website and go to the 'Returns' section. Follow the instructions provided to initiate the return process. Make sure to carefully read our return policy for eligibility and any applicable return fees.");

INSERT INTO Question (question, answer)
VALUES ("Are there any discounts available?", "We frequently offer discounts and promotions on our products. To stay updated on the latest discounts, sign up for our newsletter or follow our social media channels. Additionally, keep an eye out for seasonal sales and special events.");

INSERT INTO Question (question, answer)
VALUES ("What is your shipping policy?", "We offer fast and reliable shipping services. The shipping cost and delivery time may vary depending on your location and the chosen shipping method. During the checkout process, you will be able to see the available shipping options and their associated costs.");

INSERT INTO Question (question, answer)
VALUES ("How do I update my account information?", "To update your account information, log in to your account on our website and navigate to the 'Account Settings' or 'Profile' section. There, you can edit your personal details, shipping address, and payment information as needed.");

INSERT INTO Question (question, answer)
VALUES ("Can I cancel my order after it has been placed?", "We understand that circumstances may change. If you need to cancel your order, please contact our customer support as soon as possible. We will do our best to accommodate your request, depending on the order's status and whether it has been shipped.");
