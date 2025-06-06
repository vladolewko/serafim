 @extends('layouts.site')


@section('content')
 <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd;">
            <h3>Статус налаштувань</h3>
            <button type="button" id="check-status-btn">Перевірити статус</button>
            <div id="status-info" style="margin-top: 10px;"></div>
        </div>


        <!-- Форма налаштування відправника (додаткова) -->
        <div style="margin-top: 40px; border-top: 1px solid #ccc; padding-top: 20px;">
            <h3>Налаштування відправника (одноразово)</h3>
            <form id="setup-sender-form">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Ім'я відправника" required>
                </div>
                <div>
                    <input type="text" name="surname" placeholder="Прізвище відправника" required>
                </div>
                <div>
                    <input type="text" name="phone" placeholder="Телефон відправника" required>
                </div>
                <div>
                    <input type="text" name="city" placeholder="Місто відправника" required>
                </div>
                
                <button type="submit">Налаштувати відправника</button>
            </form>
        </div>
        <script>

         // Перевірка статусу налаштувань
        document.getElementById('check-status-btn').addEventListener('click', function() {
            fetch('{{ route("orders.checkStatus") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                let statusHtml = '<div>';
                
                // API статус
                if (data.api_key.success) {
                    statusHtml += '<p style="color: green;">✓ API ключ працює</p>';
                } else {
                    statusHtml += '<p style="color: red;">✗ Проблема з API ключем: ' + data.api_key.message + '</p>';
                }
                
                // Статус відправника
                if (data.sender_setup.is_configured) {
                    statusHtml += '<p style="color: green;">✓ Відправник налаштований</p>';
                    statusHtml += '<details><summary>Деталі</summary><pre>' + JSON.stringify(data.sender_setup.existing, null, 2) + '</pre></details>';
                } else {
                    statusHtml += '<p style="color: red;">✗ Відправник не налаштований</p>';
                    statusHtml += '<p>Відсутні: ' + data.sender_setup.missing.join(', ') + '</p>';
                }
                
                statusHtml += '</div>';
                document.getElementById('status-info').innerHTML = statusHtml;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('status-info').innerHTML = '<p style="color: red;">Помилка перевірки статусу</p>';
            });
        });

        // Обробка налаштування відправника
        document.getElementById('setup-sender-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("orders.setupSender") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Відправник налаштований успішно!');
                    this.reset();
                } else {
                    alert('Помилка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Виникла помилка при налаштуванні відправника');
            });
        });
         </script>
@endsection