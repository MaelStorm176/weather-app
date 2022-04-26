<template>
    <div class="text-white mb-8">
       <div class="places-input text-gray-800 shadow-2xl">
           <input type="text" class="w-full border-none rounded-lg" id="city-search" name="city-search" placeholder="Search cities, places...">
       </div>

        <div class="weather-container font-sans w-128 max-w-lg overflow-hidden bg-gray-900 shadow-2xl mt-4 rounded-lg">
            <div class="current-weather flex items-center justify-between px-6 py-8">
                <div class="flex items-center">
                    <div>
                        <div class="text-6xl font-semibold">{{ currentTemperature.actual }} °C</div>
                        <div class="mt-2">Feels like {{ currentTemperature.feelsLike }} °C</div>
                    </div>
                    <div class="mx-5">
                        <div class="text-2xl font-semibold">{{ currentTemperature.summary }}</div>
                        <div class="text-sm">{{ location.name }}</div>
                    </div>
                </div>
                <div>
                    <img v-bind:src="currentTemperature.icon" id="iconCurrent" alt="actualWeatherIcon"/>
                </div>
            </div> <!-- /.current-weather -->


            <div class="future-weather text-sm bg-gray-800 px-6 py-8 overflow-hidden">

                <div
                    v-for="(day, index) in daily"
                    :key="index"
                    class="flex items-center"
                    :class="{ 'mt-8' : index > 0 }"
                    v-if="index < 5"
                >
                    <div class="w-1/6 text-lg text-gray-200">{{ toDayOfWeek(day.date) }}</div>
                    <div class="w-4/6 px-4 flex items-center">
                        <div>
                            <img v-bind:src="day.icon" :id="`icon${index+1}`" width="24" height="24" alt="futureWeatherIcon"/>
                        </div>
                        <div class="ml-3">{{ day.summary }}</div>
                    </div>
                    <div class="w-1/6 text-right">
                        <div>{{ day.temperature }}°C</div>
                        <div>{{ day.temperatureApparent }}°C</div>
                    </div>
                </div>

            </div><!-- /.future-weather -->
        </div><!-- /.weather-container -->
    </div>
</template>

<script>
export default {
    mounted() {
        console.log('WeatherComponent mounted');
        const jawg = new JawgPlaces.Input({
            input: '#city-search',
            searchOnTyping: true,
            size: 5,
            onClick: (features) => {
                this.parseApiLocation(features);
                this.fetchData();
            }
        });

        //this.fetchData();
    },
    data() {
        return {
            currentDate: new Date(),
            currentTemperature: {
                actual: '',
                feelsLike: '',
                summary: '',
                icon: '',
            },
            location: {
                'name': 'Toronto, Canada',
                'lat': 43.6532,
                'lng': -79.3832
            },
            daily: [],
            //WeatherCode to summary
            weatherCodeFullDay: {
                "0": "Unknown",
                "1000": "Clear, Sunny",
                "1100": "Mostly Clear",
                "1101": "Partly Cloudy",
                "1102": "Mostly Cloudy",
                "1001": "Cloudy",
                "1103": "Partly Cloudy and Mostly Clear",
                "2100": "Light Fog",
                "2101": "Mostly Clear and Light Fog",
                "2102": "Partly Cloudy and Light Fog",
                "2103": "Mostly Cloudy and Light Fog",
                "2106": "Mostly Clear and Fog",
                "2107": "Partly Cloudy and Fog",
                "2108": "Mostly Cloudy and Fog",
                "2000": "Fog",
                "4204": "Partly Cloudy and Drizzle",
                "4203": "Mostly Clear and Drizzle",
                "4205": "Mostly Cloudy and Drizzle",
                "4000": "Drizzle",
                "4200": "Light Rain",
                "4213": "Mostly Clear and Light Rain",
                "4214": "Partly Cloudy and Light Rain",
                "4215": "Mostly Cloudy and Light Rain",
                "4209": "Mostly Clear and Rain",
                "4208": "Partly Cloudy and Rain",
                "4210": "Mostly Cloudy and Rain",
                "4001": "Rain",
                "4211": "Mostly Clear and Heavy Rain",
                "4202": "Partly Cloudy and Heavy Rain",
                "4212": "Mostly Cloudy and Heavy Rain",
                "4201": "Heavy Rain",
                "5115": "Mostly Clear and Flurries",
                "5116": "Partly Cloudy and Flurries",
                "5117": "Mostly Cloudy and Flurries",
                "5001": "Flurries",
                "5100": "Light Snow",
                "5102": "Mostly Clear and Light Snow",
                "5103": "Partly Cloudy and Light Snow",
                "5104": "Mostly Cloudy and Light Snow",
                "5122": "Drizzle and Light Snow",
                "5105": "Mostly Clear and Snow",
                "5106": "Partly Cloudy and Snow",
                "5107": "Mostly Cloudy and Snow",
                "5000": "Snow",
                "5101": "Heavy Snow",
                "5119": "Mostly Clear and Heavy Snow",
                "5120": "Partly Cloudy and Heavy Snow",
                "5121": "Mostly Cloudy and Heavy Snow",
                "5110": "Drizzle and Snow",
                "5108": "Rain and Snow",
                "5114": "Snow and Freezing Rain",
                "5112": "Snow and Ice Pellets",
                "6000": "Freezing Drizzle",
                "6003": "Mostly Clear and Freezing drizzle",
                "6002": "Partly Cloudy and Freezing drizzle",
                "6004": "Mostly Cloudy and Freezing drizzle",
                "6204": "Drizzle and Freezing Drizzle",
                "6206": "Light Rain and Freezing Drizzle",
                "6205": "Mostly Clear and Light Freezing Rain",
                "6203": "Partly Cloudy and Light Freezing Rain",
                "6209": "Mostly Cloudy and Light Freezing Rain",
                "6200": "Light Freezing Rain",
                "6213": "Mostly Clear and Freezing Rain",
                "6214": "Partly Cloudy and Freezing Rain",
                "6215": "Mostly Cloudy and Freezing Rain",
                "6001": "Freezing Rain",
                "6212": "Drizzle and Freezing Rain",
                "6220": "Light Rain and Freezing Rain",
                "6222": "Rain and Freezing Rain",
                "6207": "Mostly Clear and Heavy Freezing Rain",
                "6202": "Partly Cloudy and Heavy Freezing Rain",
                "6208": "Mostly Cloudy and Heavy Freezing Rain",
                "6201": "Heavy Freezing Rain",
                "7110": "Mostly Clear and Light Ice Pellets",
                "7111": "Partly Cloudy and Light Ice Pellets",
                "7112": "Mostly Cloudy and Light Ice Pellets",
                "7102": "Light Ice Pellets",
                "7108": "Mostly Clear and Ice Pellets",
                "7107": "Partly Cloudy and Ice Pellets",
                "7109": "Mostly Cloudy and Ice Pellets",
                "7000": "Ice Pellets",
                "7105": "Drizzle and Ice Pellets",
                "7106": "Freezing Rain and Ice Pellets",
                "7115": "Light Rain and Ice Pellets",
                "7117": "Rain and Ice Pellets",
                "7103": "Freezing Rain and Heavy Ice Pellets",
                "7113": "Mostly Clear and Heavy Ice Pellets",
                "7114": "Partly Cloudy and Heavy Ice Pellets",
                "7116": "Mostly Cloudy and Heavy Ice Pellets",
                "7101": "Heavy Ice Pellets",
                "8001": "Mostly Clear and Thunderstorm",
                "8003": "Partly Cloudy and Thunderstorm",
                "8002": "Mostly Cloudy and Thunderstorm",
                "8000": "Thunderstorm"
            },

        }
    },
    methods: {
        fetchData() {
            fetch(`/api/weather?lat=${this.location.lat}&lng=${this.location.lng}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    const timelines = data.data.timelines[0];
                    let weatherCode = timelines.intervals[0].values.weatherCodeFullDay;
                    let iconCode = null;
                    if (this.currentDate.getHours() > 18) {
                        iconCode = weatherCode + "1";
                    }else{
                        iconCode = weatherCode + "0";
                    }

                    this.currentTemperature.actual = Math.round(timelines.intervals[0].values.temperature);
                    this.currentTemperature.feelsLike = Math.round(timelines.intervals[0].values.temperatureApparent);
                    this.currentTemperature.summary = this.weatherCodeFullDay[weatherCode];
                    this.currentTemperature.icon = "icons/png/"+ iconCode + ".png";

                    this.daily = timelines.intervals.map(interval => {
                        let weatherCode = interval.values.weatherCodeFullDay;
                        let iconCode = null;
                        if (this.currentDate.getHours() > 18) {
                            iconCode = weatherCode + "1";
                        }else{
                            iconCode = weatherCode + "0";
                        }
                        return {
                            date: interval.startTime,
                            temperature: Math.round(interval.values.temperature),
                            temperatureApparent: Math.round(interval.values.temperatureApparent),
                            summary: this.weatherCodeFullDay[weatherCode],
                            icon: "icons/png/"+ iconCode + ".png"
                        }
                    });
                })
                .catch(error => console.error(error))
        },
        toDayOfWeek(date) {
            let day = new Date(date);
            return day.toLocaleDateString("en-US", { weekday: "short" }).toUpperCase();
        },
        setLocation(location) {
            if (location.lat && location.lng && location.name) {
                this.location = location;
            }else{
                console.log("Invalid location");
            }
        },
        parseApiLocation(features){
            const geometry = features.geometry;
            const name = features.properties.name;
            const country = features.properties.country;
            const location = {
                'name': name + "," + country,
                'lat': geometry.coordinates[1],
                'lng': geometry.coordinates[0],
            };
            this.setLocation(location);
        }
    }
}
</script>
