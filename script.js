document.addEventListener("DOMContentLoaded", () => {
    const calendarElement = document.getElementById('calendar');
    const memoText = document.getElementById('memoText');
    const reminderText = document.getElementById('reminderText');
    const errorMessageElement = document.getElementById('errorMessage');
    const currentMonthYearElement = document.getElementById('currentMonthYear');
    const modal = document.getElementById('reminderModal');
    const modalReminderText = document.getElementById('modalReminderText');
    const modalDate = document.getElementById('modalDate');
    const saveModalReminderButton = document.getElementById('saveModalReminder');
    const editModalReminderButton = document.getElementById('editModalReminder');
    const deleteModalReminderButton = document.getElementById('deleteModalReminder');    
    const closeModalButton = document.querySelector('.close');
    const idList = document.getElementById('idList');     
    const memoList = document.getElementById('memoList');
    const reminderList = document.getElementById('reminderList');
    let selectedDateKey = null;
    let selectedDate = null;
    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    // Function to generate the calendar
    function generateCalendar(year, month) {
        calendarElement.innerHTML = '';
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        currentMonthYearElement.textContent = `${year}年 ${month + 1}月`;

        const weekDays = ['日', '月', '火', '水', '木', '金', '土'];
        weekDays.forEach((day, index) => {
            const dayElement = document.createElement('div');
            dayElement.classList.add('day', 'header');
            dayElement.textContent = day;
            
            // 日曜日を赤色にする
            if (index === 0) {
                dayElement.classList.add('sunday');
            }

            // 土曜日を青色にする
            if (index === 6) {
                dayElement.classList.add('saturday');
            }                        
            
            calendarElement.appendChild(dayElement);
        });

        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.classList.add('day');
            calendarElement.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayElement = document.createElement('div');
            dayElement.classList.add('day');
            dayElement.textContent = day;
            dayElement.addEventListener('click', () => selectDate(year, month, day, dayElement));
            calendarElement.appendChild(dayElement);
        }
    }

    // Function to select a date and display modal with memo and reminder
    function selectDate(year, month, day, element) {
        if (selectedDate) {
            selectedDate.classList.remove('selected');
        }
        element.classList.add('selected');
        selectedDate = element;

        selectedDateKey = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        modalDate.textContent = selectedDateKey;

        // Fetch existing memo and reminder from the server
        fetch(`api.php?date=${selectedDateKey}`)
            .then(response => response.json())
            .then(data => {
                modalReminderText.value = data.reminder ? data.reminder : '';
                memoText.value = data.memo ? data.memo : '';
                idText.value = data.id ? data.id : '';                
                modal.style.display = 'block';
            })
            .catch(error => {
                displayErrorMessage('データの取得に失敗しました。');
            });
    }

    // Save reminder and memo when button is clicked
    saveModalReminderButton.addEventListener('click', () => {
        if (selectedDateKey) {
            const data = {
                date: selectedDateKey,
                id: idText.value,
                reminder: modalReminderText.value,
                memo: memoText.value
            };

            fetch('api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('データの保存に失敗しました。');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('データを保存しました');
                    modal.style.display = 'none';
                    displaySavedData();  // Call the function to update displayed data
                    window.location.reload();  // ページをリロード
                } else {
                    throw new Error(data.error || 'データの保存に失敗しました。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayErrorMessage(error.message);
            });
        }
    });
 
 
    // Edit reminder and memo when button is clicked
    editModalReminderButton.addEventListener('click', () => {
        if (selectedDateKey) {
            const data = {
                date: selectedDateKey,
                id: idText.value,
                reminder: modalReminderText.value,
                memo: memoText.value
            };

            fetch('api.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('データの更新に失敗しました。');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('データを更新しました');
                    modal.style.display = 'none';
                    displaySavedData();  // Call the function to update displayed data
                } else {
                    throw new Error(data.error || 'データの更新に失敗しました。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayErrorMessage(error.message);
            });
        }
    });

    
    // Delete reminder and memo when button is clicked
    deleteModalReminderButton.addEventListener('click', () => {
        if (selectedDateKey) {
            const data = {
                date: selectedDateKey,
                id: idText.value
            };

            fetch('api.php', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('データの削除に失敗しました。');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('データを削除しました');
                    modal.style.display = 'none';
                    displaySavedData();  // Call the function to update displayed data
                } else {
                    throw new Error(data.error || 'データの削除に失敗しました。');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayErrorMessage(error.message);
            });
        }
    });       
    
    // Close modal
    closeModalButton.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // Function to display error message
    function displayErrorMessage(message) {
        errorMessageElement.textContent = message;
        errorMessageElement.style.display = 'block';
    }

    // Function to clear error message
    function clearErrorMessage() {
        errorMessageElement.textContent = '';
        errorMessageElement.style.display = 'none';
    }

    // Function to display saved memos and reminders on the page
    function displaySavedData() {
        fetch('api.php?getAll=true')
            .then(response => response.json())
            .then(data => {
                // Clear the current lists
                idList.innerHTML = '';
                memoList.innerHTML = '';
                reminderList.innerHTML = '';

                 // Sort data by date (ascending order)
                data.memos.sort((a, b) => new Date(a.date) - new Date(b.date));               

                // Display the updated memo and reminder lists
                data.memos.forEach(item => {
                    // ID List
                    const idItem = document.createElement('li');
                    idItem.textContent = `${item.date}: ${item.id}`;
                    idList.appendChild(idItem);

                    // Memo List
                    const memoItem = document.createElement('li');
                    memoItem.textContent = `${item.date}: ${item.memo}`;
                    memoList.appendChild(memoItem);

                    // Reminder List
                    if (item.reminder) {
                        const reminderItem = document.createElement('li');
                        reminderItem.textContent = `${item.date}: ${item.reminder}`;
                        reminderList.appendChild(reminderItem);
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                displayErrorMessage('保存したデータの取得に失敗しました。');
            });
    }
    
    // 前の月を表示
    function previousMonth() {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        generateCalendar(currentYear, currentMonth);
    }

    // 次の月を表示
    function nextMonth() {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        generateCalendar(currentYear, currentMonth);
    }
    
    document.getElementById('prevMonth').addEventListener('click', previousMonth);
    document.getElementById('nextMonth').addEventListener('click', nextMonth);

    // Initialize calendar
    generateCalendar(currentYear, currentMonth);
    displaySavedData();  // Display data when the page loads
});
