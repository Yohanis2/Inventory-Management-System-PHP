<?php include 'header.php'; ?>
<style>
    .calendar-days {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        min-height: 120px;
    }

    .calendar-day {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        transition: 0.2s ease;
    }

    .calendar-day:hover {
        background-color: #e2e6ea;
        cursor: pointer;
    }

    .calendar-today {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        border: 2px solid #0056b3;
    }
</style>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><i class="fas fa-concierge-bell"></i> Services</h2>
            <nav>
                <img src="img/logo1.jpg" alt="BU Service Logo" class="img-fluid">
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="main">
                <h1>The Services of Inventory Management System</h1>
                <p>
                    BU Web-Based IMS allows users to track and manage their inventory by recording detailed information about each item, such as:
                </p>
                <ul>
                    <li>Item descriptions</li>
                    <li>Quantities</li>
                    <li>Locations</li>
                    <li>Serial numbers</li>
                    <li>Other relevant data</li>
                </ul>
                <p>
                    It provides real-time visibility into stock levels, helping to prevent stockouts or overstocking. Additionally, it offers:
                </p>
                <ul>
                    <li>
                        Comprehensive reporting and analytics capabilities, enabling users to generate various reports related to:
                        <ul>
                            <li>Inventory levels</li>
                            <li>Stock movement</li>
                            <li>Other key performance indicators</li>
                        </ul>
                    </li>
                    <li>Insights into inventory management practices to facilitate data-driven decision-making.</li>
                    <li>
                        User access controls and permissions, allowing different users or roles within the organization to have appropriate levels of access to the system. This feature enhances:
                        <ul>
                            <li>Data security</li>
                            <li>Data integrity</li>
                            <li>Restrictions against unauthorized access to sensitive inventory information</li>
                        </ul>
                    </li>
                </ul>
                <p>
                    A "request item" refers to an inventory item that has been formally requested for replenishment or procurement. This can occur when stock levels fall below a predefined threshold or when a specific item is needed for operational purposes.
                </p>
            </div>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="calendar">
                <h3><i class="fas fa-calendar-alt"></i> Calendar</h3>
                <button id="prev-month" class="nav-button">Previous</button>
                <button id="next-month" class="nav-button">Next</button>
                <div id="calendar-header" class="my-2"></div>
                <div class="calendar-days d-flex flex-wrap gap-1" id="calendar-days" style="min-height: 100px;"></div>
            </div>
            <div class="image mt-4">
                <img src="img/invntory2.jpg" alt="Inventory Visual" class="img-fluid">
            </div>
        </aside>
    </div>
</main>

<!-- Calendar Script -->
<script>
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    function generateCalendar() {
        const calendarHeader = document.getElementById("calendar-header");
        const calendarDays = document.getElementById("calendar-days");

        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const monthName = new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' });

        const today = new Date();
        const todayDate = today.getDate();
        const isCurrentMonth = currentMonth === today.getMonth() && currentYear === today.getFullYear();

        calendarHeader.innerHTML = `<h4>${monthName} ${currentYear}</h4>`;
        calendarDays.innerHTML = "";

        for (let i = 1; i <= daysInMonth; i++) {
            let dayDiv = document.createElement("div");
            dayDiv.textContent = i;
            dayDiv.style.width = "30px";
            dayDiv.style.height = "30px";
            dayDiv.style.display = "flex";
            dayDiv.style.alignItems = "center";
            dayDiv.style.justifyContent = "center";
            dayDiv.style.border = "1px solid #ccc";
            dayDiv.style.borderRadius = "4px";
            dayDiv.style.backgroundColor = "#f8f9fa";
            dayDiv.style.margin = "2px";

            // Highlight today's date
            if (isCurrentMonth && i === todayDate) {
                dayDiv.style.backgroundColor = "#007bff";
                dayDiv.style.color = "white";
                dayDiv.style.fontWeight = "bold";
                dayDiv.style.border = "2px solid #0056b3";
            }

            calendarDays.appendChild(dayDiv);
        }
    }

    document.getElementById('prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        generateCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        generateCalendar();
    });

    generateCalendar();
</script>


<?php include 'footer.php'; ?>
