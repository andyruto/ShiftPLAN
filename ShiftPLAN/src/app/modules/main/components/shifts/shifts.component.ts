/**
 * shifts.component.ts
 * 
 *  Main typescript class for the shifts component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-28 / Anne Naumann
 */
import { Component, OnInit, AfterViewInit, HostListener } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { Router } from '@angular/router'
import { UsertypeService } from 'src/app/services/usertype.service';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { MatDialog } from '@angular/material/dialog';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';
import { GetShiftsResponse } from 'src/app/models/getshiftsresponse copy';

@Component({
  selector: 'app-shifts',
  templateUrl: './shifts.component.html',
  styleUrls: ['./shifts.component.scss']
})
export class ShiftsComponent implements OnInit, AfterViewInit {

  shifts: {id: number, weekday: string; date: string, start: string, end: string, task: string, employees: string[]}[] = 
    [{
      id: 1,
      weekday: 'Montag', 
      date: '05.07', 
      start: '08:00', 
      end: '14:00', 
      task: 'Empfang_01', 
      employees : ['ag167', 'an055', 'sw210']}
    ]

  title = ''
  wordClock = ''
  admin: boolean = false

  constructor(
    private translate: TranslateService, 
    private router : Router,
    private usertype : UsertypeService, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService
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
      this.wordClock = translation.WordClock
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

    //get public key
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
    getShiftsAnswer = await this.api.sendPostRequest<GetShiftsResponse>(
      'shifts/get/', {
        apiKey: apiKey,
        session: sessionAsync,
      }
    );
    getShiftsPromise = await getShiftsAnswer.toPromise();
    getShiftsErrorCode = getShiftsPromise.errorCode;

    //add shifts to UI
    this.shifts = getShiftsPromise.shifts;
  }
}
