const MlCalendar = {
    /*html*/
    template: `
    <div class="multilist_calendar">
        <div class="month">
            <ul>
                <li class="prev" @click="prev();">&#10094;</li>
                <li class="next" @click="next();">&#10095;</li>
                <li>
                {{ getselectedMonth().month }}<br>
                <span style="font-size:18px">{{ getselectedMonth().year }}</span>
                </li>
            </ul>
        </div>
        <ul class="weekdays">
            <li>Dimanche</li>
            <li>Lundi</li>
            <li>Mardi</li>
            <li>Mercredi</li>
            <li>Jeudi</li>
            <li>Vendredi</li>
            <li>Samedi</li>
        </ul>
        <ul class="days" v-if="!loader">
            <li v-if="dates.length>0" v-for="i of dates[0].day"></li>
            <li v-for="(d,i) of dates"><span @click="dateClick(d)" :class="d.is_booked?'notdispo':'dispo'">{{ addLeadingZeros(i+1,2) }}</span></li>
        </ul>
        <div class="days_loader" v-if="loader">
            <div class="loader"></div>
        <div>
    </div>

    `,
    props: {
        id: 0
    },
    data() {
        return {
            months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            date: new Date(),
            month_index: 0,
            dates: [],
            loader: false,
        }
    },
    computed: {},
    watch: {

    },
    beforeMount(){

    },
    mounted() {
        this.getAllDaysInMonth();
    },
    methods: {
        next(){
            this.month_index++;
            this.getAllDaysInMonth();
        },
        prev(){
            this.month_index--;
            this.getAllDaysInMonth();
        },
        getAllDaysInMonth() {
            const year = new Date(new Date().setMonth(new Date().getMonth()+this.month_index)).getFullYear();
            const month = new Date(new Date().setMonth(new Date().getMonth()+this.month_index)).getMonth();
            const date = new Date(year, month, 1);

            const dates = [];

            this.loader=true;
            axios.get(`/api/v2/getAdCalendar?id=${this.id}&year=${year}&month=${month+1}`)
                .then((response) => {
                    if (response.data.success == true) {
                        const bookedDays = response.data.data.map(v=> new Date(v));
                        while (date.getMonth() === month) {
                            let is_booked = false;
                            const thisDate = new Date(date);
                            for(const v of bookedDays){
                                v.setHours(0,0,0,0);
                                thisDate.setHours(0,0,0,0);
                                if(v.getTime()==thisDate.getTime()){
                                    is_booked = true;
                                }
                            }
                            dates.push({day:thisDate.getDay(),is_booked,date:moment(thisDate).format('YYYY-MM-DD')});
                            date.setDate(date.getDate() + 1);
                        }
                        this.loader=false;
                        this.dates = dates;
                        console.log(this.dates);
                    }
                })
                .catch((error) => {
                    this.loader=false;
                    console.log(error);
                    this.dates = [];
                });

        },
        getselectedMonth(){
            let date = new Date(new Date().setMonth(new Date().getMonth()+this.month_index));
            return {
                month: this.months[date.getMonth()],
                year: date.getFullYear(),
            };
        },
        addLeadingZeros(num, totalLength) {
            return String(num).padStart(totalLength, '0');
        },
        dateClick(d){
            if(d.is_booked) return;
            this.$emit("dateClick", {
                date: d.date,
                minDate:this.dates[0].date,
                maxDate:this.dates[this.dates.length-1].date,
                disableDates:this.dates.filter(v=>v.is_booked).map(v=>v.date)
            });
        },
    }
};
