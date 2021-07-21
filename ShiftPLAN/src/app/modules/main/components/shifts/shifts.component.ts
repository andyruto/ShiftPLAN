/**
 * shifts.component.ts
 * 
 *  Main typescript class for the shifts component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-28 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit, HostListener, ChangeDetectorRef } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router'
import { UsertypeService } from 'src/app/services/usertype.service';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { MatDialog } from '@angular/material/dialog';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { GetShiftsResponse } from 'src/app/models/getshiftsresponse';
import {DatePipe, formatDate } from '@angular/common';
import { GetTaskResponse } from 'src/app/models/gettaskresponse';
import { GetUserDataResponse } from 'src/app/models/getuserdataresponse';
import { GetTasksResponse } from 'src/app/models/gettasksresponse';
import { Shifts } from 'src/app/models/shifts';

@Component({
  selector: 'app-shifts',
  templateUrl: './shifts.component.html',
  styleUrls: ['./shifts.component.scss']
})
export class ShiftsComponent implements OnInit, AfterViewInit {

  shifts!: Shifts[];

  title = ''
  wordClock = ''
  admin: boolean = false

  monday: string = '';
  tuesday: string = '';
  wednesday: string = '';
  thursday: string = '';
  friday: string = '';
  saturday: string = '';
  sunday: string = '';

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private usertype : UsertypeService, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService,
    private datepipe: DatePipe
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Shifts_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('Shifts_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.Shifts;
      this.wordClock = translation.WordClock;
      this.monday = translation.Shifts.Monday;
      this.tuesday = translation.Shifts.Tuesday;
      this.wednesday = translation.Shifts.Wednesday;
      this.thursday = translation.Shifts.Thursday;
      this.friday = translation.Shifts.Friday;
      this.saturday = translation.Shifts.Saturday;
      this.sunday = translation.Shifts.Sunday;

      this.refreshShifts();
    });
    this.checkBtn();
  }

  ngAfterViewInit(): void{
    this.setScreenSize();
  }

  @HostListener('window:resize', ['$event'])
  setScreenSize() {
    let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
    let bottomBar = document.getElementsByTagName('app-bottom-bar')[0].clientHeight
    var scrollView = document.getElementById('shiftsScrollView')

    let screenHeight = window.innerHeight-toolbar-bottomBar
    scrollView!.style.height = screenHeight + 'px'
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  private async checkBtn() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Shifts_spinnerButtonCheck',
      autoFocus: false,
      disableClose: true
    });

    this.admin = (await this.usertype.getShown()).admin;

    //close spinner
    this.dialog.getDialogById('Shifts_spinnerButtonCheck')?.close();
  }

  navigateToShift(){

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Shifts_spinnerNavigate_shifts-shift',
      autoFocus: false,
      disableClose: true
    });

    this.router.navigate(['/app/shifts-shift'])
  }

  navigateToAddShift(){

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Shifts_spinnerNavigate_shifts-add',
      autoFocus: false,
      disableClose: true
    });

    this.router.navigate(['/app/shifts-add'])
  }

  private async refreshShifts() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Shifts_spinnerRefresh',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let getShiftsAnswer;
    let publicKeyPromise;
    let getShiftsPromise;

    let publicKeyErrorCode: number;
    let getShiftsErrorCode: number;
    let session: string = localStorage.getItem('Session') as string;
    let sessionAsync: string;
    let publicKey: string;
    let apiKey: string = localStorage.getItem('APIKey') as string;
    var count: number;

    //get public key
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
      'key/publickey/', {
        apiKey: apiKey
      }
    );
    publicKeyPromise = await publicKeyAnswer.toPromise();
    publicKey = publicKeyPromise.publicKey;
    publicKeyErrorCode = publicKeyPromise.errorCode;

    //encrypt session asyncronous
    sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

    //get shifts from api
    count = Math.random() * 101;
    do {
      await this.delay(count);
    }while(this.api.isBusy);
    getShiftsAnswer = await this.api.sendPostRequest<GetShiftsResponse>(
      'shifts/get/', {
        apiKey: apiKey,
        session: sessionAsync,
      }
    );
    getShiftsPromise = await getShiftsAnswer.toPromise();
    //DEBUG
    console.log(getShiftsPromise)
    getShiftsErrorCode = getShiftsPromise.errorCode;

    //add shifts to UI
    this.shifts = [];

    for(let i = 0; i < getShiftsPromise.shifts.length; i++) {
      //variables
      let element = getShiftsPromise.shifts[i];
      let weekday: string;
      let startDate: Date = new Date(element.shiftStart.date);
      let endDate: Date = new Date(element.shiftEnd.date);
      let dateString: string;
      let start: string;
      let end: string;
      let taskName: string;
      let userName: string;
      let present: Shifts;

      //getUser
      //variables
      let publicKeyAnswer;
      let getUserDataAnswer;
      let publicKeyPromise;
      let getUserDataPromise;

      let publicKeyErrorCode: number;
      let getUserDataErrorCode: number;
      let session: string = localStorage.getItem('Session') as string;
      let sessionAsync: string;
      let publicKey: string;
      let apiKey: string = localStorage.getItem('APIKey') as string;

      //get public key
      count = Math.random() * 101;
      do {
        await this.delay(count);
      }while(this.api.isBusy);
      publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
        'key/publickey/', {
          apiKey: apiKey
        }
      );
      publicKeyPromise = await publicKeyAnswer.toPromise();
      publicKey = publicKeyPromise.publicKey;
      publicKeyErrorCode = publicKeyPromise.errorCode;
      
      //encrypt session asyncronous
      sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey)

      //get userdata from api
      count = Math.random() * 101;
      do {
        await this.delay(count);
      }while(this.api.isBusy);
      getUserDataAnswer = await this.api.sendPostRequest<GetUserDataResponse>(
        'users/get/', {
          apiKey: apiKey,
          session: sessionAsync,
          filter: 'id',
          value: element.assignedUser
        }
      );
      getUserDataPromise = await getUserDataAnswer.toPromise();
      getUserDataErrorCode = getUserDataPromise.errorCode;
      userName = getUserDataPromise.profiles[0].name;
      //DEBUG
      console.log(userName)

      //check if shift already present
      present = this.shifts.find(x => x.connectedTaskId === element.connectedTaskId && x.shiftStartDate === element.shiftStart.date && x.shiftEndDate === element.shiftEnd.date) as Shifts;
      if(present) {
        let newEmployee: string = userName;
        this.shifts[this.shifts.findIndex(x => x.id === present.id)].employees = [...this.shifts[this.shifts.findIndex(x => x.id === present.id)].employees, newEmployee];
      }else {
        //get weekday
        switch(startDate.getDay()) {
          case 1: {
            weekday = this.monday;
            break;
          }
          case 2: {
            weekday = this.tuesday;
            break;
          }
          case 3: {
            weekday = this.wednesday;
            break;
          }
          case 4: {
            weekday = this.thursday;
            break;
          }
          case 5: {
            weekday = this.friday;
            break;
          }
          case 6: {
            weekday = this.saturday;
            break;
          }
          default: {
            weekday = this.sunday;
            break;
          }
        }

        //get dateString
        dateString = this.datepipe.transform(startDate, 'dd.MM.yyyy') as string;

        //get start & end
        start = this.datepipe.transform(startDate, 'H:mm') as string;
        end = this.datepipe.transform(endDate, 'H:mm') as string;

        //get task
        //variables

        let getTaskAnswer;
        let getTaskPromise;
        let getTaskErrorCode: number;

        //get task from api
        count = Math.random() * 101;
        do {
          await this.delay(count);
        }while(this.api.isBusy);
        getTaskAnswer = await this.api.sendPostRequest<GetTasksResponse>(
          'tasks/get/', {
            apiKey: apiKey,
            session: sessionAsync,
            id: element.connectedTaskId
          }
        );
        getTaskPromise = await getTaskAnswer.toPromise();
        getTaskErrorCode = getTaskPromise.errorCode;

        taskName = getTaskPromise.tasks[0].name;
        //DEBUG
        console.log(taskName)

        let newObj: Shifts = {
          id: element.id, 
          weekday: weekday, 
          date: dateString, 
          start: start, 
          end: end, 
          task: taskName, 
          employees: [userName],
          shiftStartDate: element.shiftStart.date,
          shiftEndDate: element.shiftEnd.date,
          connectedTaskId: element.connectedTaskId
        };
        this.shifts = [...this.shifts, newObj];

        //DEBUG
        console.log(this.shifts)
      

        //FOR API-UPDATE:

        // getShiftsPromise.shifts.forEach(async (element) => {

        //   //DEBUG
        //   console.log("start");

        //   //variables
        //   let weekday: string;
        //   let startDate: Date = new Date(element.shiftStart.date);
        //   let endDate: Date = new Date(element.shiftEnd.date);
        //   let dateString: string;
        //   let start: string;
        //   let end: string;
        //   let taskName: string;
        //   let userName: string;

        //   //get weekday
        //   switch(startDate.getDay()) {
        //     case 1: {
        //       weekday = this.monday;
        //       break;
        //     }
        //     case 2: {
        //       weekday = this.tuesday;
        //       break;
        //     }
        //     case 3: {
        //       weekday = this.wednesday;
        //       break;
        //     }
        //     case 4: {
        //       weekday = this.thursday;
        //       break;
        //     }
        //     case 5: {
        //       weekday = this.friday;
        //       break;
        //     }
        //     case 6: {
        //       weekday = this.saturday;
        //       break;
        //     }
        //     default: {
        //       weekday = this.sunday;
        //       break;
        //     }
        //   }

        //   //get dateString
        //   dateString = this.datepipe.transform(startDate, 'dd.MM.yyyy') as string;

        //   //get start & end
        //   start = this.datepipe.transform(startDate, 'H:mm') as string;
        //   end = this.datepipe.transform(endDate, 'H:mm') as string;

        //   //get task
        //   //variables
        //   let publicKeyAnswer;
        //   let getTaskAnswer;
        //   let publicKeyPromise;
        //   let getTaskPromise;

        //   let publicKeyErrorCode: number;
        //   let getTaskErrorCode: number;
        //   let session: string = localStorage.getItem('Session') as string;
        //   let sessionAsync: string;
        //   let publicKey: string;
        //   let apiKey: string = localStorage.getItem('APIKey') as string;

        //   //get public key
        //   publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
        //     'key/publickey/', {
        //       apiKey: apiKey
        //     }
        //   );
        //   publicKeyPromise = await publicKeyAnswer.toPromise();
        //   publicKey = publicKeyPromise.publicKey;
        //   publicKeyErrorCode = publicKeyPromise.errorCode;

        //   //encrypt session asyncronous
        //   sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey);

        //   //get task from api
        //   getTaskAnswer = await this.api.sendPostRequest<GetTasksResponse>(
        //     'tasks/get/', {
        //       apiKey: apiKey,
        //       session: sessionAsync,
        //       id: element.connectedTaskId
        //     }
        //   );
        //   getTaskPromise = await getTaskAnswer.toPromise();
        //   getTaskErrorCode = getTaskPromise.errorCode;

        //   taskName = getTaskPromise.tasks[0].name;
        //   //DEBUG
        //   console.log(taskName)

        //   //getUser
        //   //variables
        //   let getUserDataAnswer;
        //   let getUserDataPromise;

        //   let getUserDataErrorCode: number;

        //   //get public key
        //   publicKeyAnswer = await this.api.sendPostRequest<PublicKeyResponse>(
        //     'key/publickey/', {
        //       apiKey: apiKey
        //     }
        //   );
        //   publicKeyPromise = await publicKeyAnswer.toPromise();
        //   publicKey = publicKeyPromise.publicKey;
        //   publicKeyErrorCode = publicKeyPromise.errorCode;

        //   //encrypt session asyncronous
        //   sessionAsync = await this.encrypt.encryptTextAsync(session, publicKey)

        //   //get userdata from api
        //   getUserDataAnswer = await this.api.sendPostRequest<GetUserDataResponse>(
        //     'users/get/', {
        //       apiKey: apiKey,
        //       session: sessionAsync,
        //       filter: 'id',
        //       value: element.assignedUser
        //     }
        //   );
        //   getUserDataPromise = await getUserDataAnswer.toPromise();
        //   getUserDataErrorCode = getUserDataPromise.errorCode;
        //   userName = getUserDataPromise.profiles[0].name;
        //   //DEBUG
        //   console.log(userName)

        //   this.shifts.push(...[{id: element.id, weekday: weekday, date: dateString, start: start, end: end, task: taskName, employees: [userName]}]);

        //   //DEBUG
        //   console.log(this.shifts)
        // });
      }
    }
    //close spinner
    this.dialog.getDialogById('Shifts_spinnerRefresh')?.close();
  }

  private delay(ms: number) {
    return new Promise( resolve => setTimeout(resolve, ms) );
  }
}

