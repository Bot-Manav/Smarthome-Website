# Smart Home Connect

**Smart Home Connect** is a web-based dashboard designed to centralize and simplify the management of your smart home devices. Built with PHP and MySQL, it offers a secure and intuitive interface for controlling various smart appliances like Lights, Fans, Air Conditioners, and TVs.

## Features

-   **User Authentication**: Secure login and registration system to keep your smart home controls private.
-   **Dashboard Overview**: Get a quick glance at the status of all your connected devices.
-   **Device Controls**:
    -   **Toggle On/Off**: Switch devices on or off with a single click.
    -   **Advanced Controls**: Adjust fan speeds, set AC temperatures, and control TV volume.
-   **Device Management**: Add new devices to your network and customize their names.
-   **Responsive Design**: optimized for both desktop and mobile use.

## Technologies Used

-   **Frontend**: HTML5, CSS3, Vanilla JavaScript.
-   **Backend**: PHP.
-   **Database**: MySQL.
-   **Styling**: Custom CSS with Google Fonts (Outfit).

## Prerequisities

To run this project locally, you need a local server environment. We recommend **XAMPP**.

-   [XAMPP Download](https://www.apachefriends.org/index.html)

## Installation & Setup

1.  **Clone the Repository** (or download the source code):
    ```bash
    git clone <your-repo-url>
    ```

2.  **Move Files to Server Directory**:
    -   Copy the project folder `smarthome` to your XAMPP `htdocs` directory.
    -   Path: `C:\xampp\htdocs\smarthome`

3.  **Database Setup**:
    -   Open your browser and navigate to `http://localhost/phpmyadmin`.
    -   Create a new database named `smarthome_db`.
    -   Import the `database.sql` file provided in the project root into this new database.
    -   *Alternatively*, you can run the SQL commands inside `database.sql` manually in the SQL tab.

4.  **Database Configuration**:
    -   Open `db.php` and ensure the credentials match your local MySQL configuration (default XAMPP settings are usually: Host: `localhost`, User: `root`, Password: ``).

5.  **Run the Application**:
    -   Start **Apache** and **MySQL** modules from the XAMPP Control Panel.
    -   Open your browser and go to:
        ```
        http://localhost/smarthome
        ```

## Usage

1.  **Register/Login**: Create a new account or log in with the test credentials (if you ran the sample data insert).
    -   Default Test User: `testuser` / `password`
2.  **Manage Devices**: Use the "Manage Devices" button to add appliances to your dashboard.
3.  **Control**: Use the toggles and sliders on the dashboard to control your devices.

## Project Structure

-   `index.php`: Main dashboard.
-   `devices.php`: Device management interface.
-   `toggle.php`: Logic for controlling device states.
-   `login.php` / `register.php`: Authentication pages.
-   `styles.css`: Main stylesheet.
-   `database.sql`: Database schema and initial seed data.

## License

This project is licensed under the MIT License.
