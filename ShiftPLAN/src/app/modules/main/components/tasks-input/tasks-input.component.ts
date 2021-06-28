/**
 * tasks-input.component.ts
 * 
 *  Main typescript class for the tasks-input component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-28 / Anne Naumann
 */
import { Component, OnInit, AfterViewChecked, Inject } from '@angular/core';
import { SwiperConfigInterface } from 'ngx-swiper-wrapper';
import { NgxMaterialTimepickerTheme } from 'ngx-material-timepicker';
import { MatDialog, MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';

export interface DialogData {
  selectedDay: number;
  existingDays: [
    monday: boolean, 
    tuesday: boolean,
    wednesday: boolean, 
    thursday: boolean,
    friday: boolean,
    saturday: boolean,
    sunday: boolean
  ]
}

@Component({
  selector: 'app-tasks-input',
  templateUrl: './tasks-input.component.html',
  styleUrls: ['./tasks-input.component.scss'],
})
export class TasksInputComponent implements OnInit, AfterViewChecked {

  public config: SwiperConfigInterface = {
    direction: 'horizontal',
    slidesPerView: 1,
    navigation: true,
    //keyboard: true,
    //mousewheel: true,
  };

  timePickerTheme: NgxMaterialTimepickerTheme = {
    container: {
        bodyBackgroundColor: 'white',
        buttonColor: '#6a9be6'
    },
    dial: {
        dialBackgroundColor: '#6a9be6',
    },
    clockFace: {
        clockFaceBackgroundColor: '#F6F6F6',
        clockHandColor: '#e6a21a',
        clockFaceTimeInactiveColor: 'black'
    }
  };

  viewLoaded = false
  contentLoaded = false
  screenHeight = 0
  selectedDay = 0

  mondayName = ''
  tuesdayName = ''
  wednesdayName = ''
  thursdayName = ''
  fridayName = ''
  saturdayName = ''
  sundayName = ''

  weekdayNames = [{index: 0, name: ''}]

  validityDates = [
    {date: new Date('2021-05-18'), days : [
      {index: 0, name: 'Monday', timespans : [
        {start: '10:00', end: '18:00', persons: 2},
        {start: '10:30', end: '18:30', persons: 1}
      ]},
      {index: 1, name: 'Tuesday', timespans : [
        {start: '13:00', end: '21:00', persons: 2}
      ]}
    ]}, 
    {date: new Date('2021-06-16'), days : [
      {index: 0, name: 'Monday', timespans : [
        {start: '07:30', end: '16:45', persons: 2}
      ]}
    ]}
  ]

  constructor(public dialog: MatDialog, private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
      this.mondayName = translation.Weekdays.Monday;
      this.tuesdayName = translation.Weekdays.Tuesday;
      this.wednesdayName = translation.Weekdays.Wednesday;
      this.thursdayName = translation.Weekdays.Thursday;
      this.fridayName = translation.Weekdays.Friday;
      this.saturdayName = translation.Weekdays.Saturday;
      this.sundayName = translation.Weekdays.Sunday;
    });
  }

  ngAfterViewChecked(): void{
    let container =  document.getElementById('inputContainer')?.clientHeight
    let dateInput = document.getElementsByClassName('upperContainer')[0].clientHeight
    let scrollViews = document.querySelectorAll('.scrollView')

    if(container! > dateInput && this.viewLoaded == false){
      this.screenHeight = container!-dateInput
      this.viewLoaded = true
    }
    scrollViews.forEach(elem => {elem.setAttribute('style', 'height: ' + this.screenHeight + 'px')})

    if(this.mondayName != '' && this.contentLoaded == false){
      var content = [
        {index: 0, name: this.mondayName},
        {index: 1, name: this.tuesdayName},
        {index: 2, name: this.wednesdayName},
        {index: 3, name: this.thursdayName},
        {index: 4, name: this.fridayName},
        {index: 5, name: this.saturdayName},
        {index: 6, name: this.sundayName}
      ]
      this.weekdayNames = [...content]
      this.contentLoaded = true
    }
  }

  addValidityDate(dateIndex: number){
    var date = {index: dateIndex, date: new Date(), days : [
      {index: 0, name: 'Monday', timespans : [
        {index: 0, start: '', end: '', persons: 0}
      ]}
    ]}
    this.validityDates.push(date)
  }

  addDay(dateIndex: number, weekday: number){
    var day = {index: this.weekdayNames[weekday].index , name: this.weekdayNames[weekday].name, timespans: [
      {index: 0, start: '', end: '', persons: 0}
    ]}
    this.validityDates[dateIndex].days = [...this.validityDates[dateIndex].days, (day)]
    this.validityDates[dateIndex].days.sort((a,b)=> a.index-b.index)
  }

  addTimespan(dateIndex: number, weekdayIndex: number, timeIndex: number){
    var time = {index: timeIndex, start: '', end: '', persons: 0}
    this.validityDates[dateIndex].days[weekdayIndex].timespans.push(time)
  }

  removeValidityDate(dateIndex: number){
    this.validityDates.splice(dateIndex, 1)
  }

  removeTimespan(dateIndex: number, weekdayIndex: number, timespanIndex: number){
    this.validityDates[dateIndex].days[weekdayIndex].timespans.splice(timespanIndex, 1)
  }

  showDialog(dateIndex: number){
    var dayExists = [false, false, false, false, false, false, false]
    let days = this.validityDates[dateIndex].days
    for(const elem of days){
      dayExists[elem.index] = true
    }

    const dialogRef = this.dialog.open(TasksInputDialog, {
      autoFocus: false,
      data: {selectedDay: this.selectedDay, existingDays: dayExists}
    });

    dialogRef.afterClosed().subscribe(result => {
      if(result != null){
        this.selectedDay = result.selectedDay;
        this.addDay(dateIndex, this.selectedDay)
      }
    });
  }
}

@Component({
  selector: 'tasks-input-dialog',
  templateUrl: 'tasks-input-dialog.html',
  styleUrls: ['./tasks-input.component.scss'],
})
export class TasksInputDialog {

  constructor(public dialogRef: MatDialogRef<TasksInputDialog>,
    @Inject(MAT_DIALOG_DATA) public data: DialogData) {}

  selectDay(weekdayIndex: number){
    this.data.selectedDay = weekdayIndex
    this.dialogRef.close(this.data);
  }
}