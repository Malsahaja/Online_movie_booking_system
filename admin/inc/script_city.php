<script>
        // Cities data based on states
        const cities = {
            "Johor": ["Johor Bahru", "Batu Pahat", "Kluang"],
            "Kedah": ["Alor Setar", "Sungai Petani", "Kulim"],
            "Kelantan": ["Kota Bharu", "Tanah Merah", "Gua Musang"],
            "Wilayah Persekutuan Kuala Lumpur": ["Kuala Lumpur"],
            "Labuan": ["Labuan"],
            "Melaka": ["Melaka"],
            "Negeri Sembilan": ["Seremban", "Port Dickson", "Nilai", "Bandar Enstek"],
            "Pahang": ["Kuantan", "Bentong", "Temerloh", "Karak"],
            "Penang": ["George Town", "Butterworth", "Bukit Mertajam"],
            "Perak": ["Ipoh", "Taiping", "Teluk Intan"],
            "Perlis": ["Kangar"],
            "Wilayah Persekutuan Putrajaya": ["Putrajaya"],
            "Sabah": ["Kota Kinabalu", "Sandakan", "Tawau"],
            "Sarawak": ["Kuching", "Miri", "Sibu"],
            "Selangor": ["Shah Alam", "Petaling Jaya","Puchong", "Subang Jaya"],
            "Terengganu": ["Kuala Terengganu", "Dungun", "Kemaman"]
        };

        // Update city options based on selected state
        document.getElementById('negeri').addEventListener('change', function() {
            const state = this.value;
            const citySelect = document.getElementById('city');
            citySelect.innerHTML = '<option value="">Select Bandar/City</option>'; // Clear current options

            if (state && cities[state]) {
                cities[state].forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.text = city;
                    citySelect.appendChild(option);
                });
            }
        });

        // Set initial cities if a state was previously selected
        const initialState = "<?php echo $negeri; ?>";
        const initialCity = "<?php echo $city; ?>";
        if (initialState) {
            document.getElementById('negeri').value = initialState;
            const citySelect = document.getElementById('city');
            cities[initialState].forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.text = city;
                if (city === initialCity) {
                    option.selected = true;
                }
                citySelect.appendChild(option);
            });
        }
    </script>