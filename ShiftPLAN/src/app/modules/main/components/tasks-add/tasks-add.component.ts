/**
 * tasks-add.component.ts
 * 
 *  Main typescript class for the tasks-add component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { Location } from '@angular/common';
import { Component, OnInit, AfterViewChecked, HostListener } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { GeneralResponse } from 'src/app/models/generalresponse';
import { PublicKeyResponse } from 'src/app/models/publickeyresponse';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { ApiService } from 'src/app/services/api.service';
import { EncryptionService } from 'src/app/services/encryption.service';

@Component({
  selector: 'app-tasks-add',
  templateUrl: './tasks-add.component.html',
  styleUrls: ['./tasks-add.component.scss']
})

export class TasksAddComponent implements OnInit, AfterViewChecked {

  viewLoaded = false

  title = ''
  saveBtn = ''
  labelDescription = ''
  checkBoxOnce = ''
  labelHint = ''
  checked: boolean = false;
  description: string = '';
  position: string = 'abolute';
  originalHeight: number = window.innerHeight;

  constructor(
    private translate: TranslateService, 
    public dialog: MatDialog,
    private api: ApiService,
    private encrypt: EncryptionService,
    private location: Location
    ) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'TasksAdd_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('TasksAdd_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.TasksAdd;
      this.labelDescription = translation.Tasks.Add.LabelDescription;
      this.checkBoxOnce = translation.Tasks.Add.CheckBoxOnce;
      this.labelHint = translation.Tasks.Add.LabelHint;
      this.saveBtn = translation.SaveButton;
    });
  }

  ngAfterViewChecked(): void{
    let btn = document.getElementById('saveTaskBtn')

    if(btn!.clientHeight != 0 && this.viewLoaded == false){
      let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
      let nameInput = document.getElementsByTagName('mat-form-field')[0].clientHeight
      let checkBox = document.getElementsByTagName('mat-checkbox')[0].clientHeight
      let buttonContainer = document.getElementById('buttonContainer')?.clientHeight
      var inputContainer = document.getElementById('inputContainer')
  
      let screenHeight = window.innerHeight-toolbar-nameInput-checkBox-buttonContainer!
      inputContainer!.style.height = screenHeight + 'px'

      this.viewLoaded = true
    }
  }

  ngOnDestroy() {
    this.dialog.closeAll();
  }

  @HostListener('window:resize', ['$event'])
  setBtnPosition() {
    let currentHeight: number = window.innerHeight;
    if(currentHeight < this.originalHeight) {
      this.position = 'static';
    }else {
      this.position = 'absolute';
    }
  }

  showHint(isChecked: boolean): void{
    var hintDiv = document.getElementById('taskOnceHint')
    var inputDiv = document.getElementById('inputContainer')

    if(isChecked){
      this.checked = true;
      hintDiv!.style.display = 'block'
      inputDiv!.style.visibility ='hidden'
    }else{
      this.checked = false;
      hintDiv!.style.display = 'none'
      inputDiv!.style.display = 'block'
    }
  }

  public async saveTask() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinnerAdd',
      autoFocus: false,
      disableClose: true
    });

    //variables
    let publicKeyAnswer;
    let addTaskAnswer;
    let publicKeyPromise;
    let addTaskPromise;

    let publicKeyErrorCode: number;
    let addTaskErrorCode: number;
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

    if(this.checked) {

      //check Inputs
      if(this.description == '') {
        this.dialog.open(InvalidInputDialog, {
          autoFocus: false
        });

        //close spinner
        this.dialog.getDialogById('Tasks_spinnerAdd')?.close();

        return
      }

      //send add task request
      addTaskAnswer = await this.api.sendPostRequest<GeneralResponse>(
        'tasks/create/', {
          apiKey: apiKey,
          session: sessionAsync,
          name: this.description
        }
      );
      addTaskPromise = await addTaskAnswer.toPromise();
      addTaskErrorCode = addTaskPromise.errorCode;
    }

    this.location.back();
  }
}

@Component({
  selector: 'invalid-data-dialog',
  templateUrl: 'dialog.html',
  styleUrls: ['./tasks-add.component.scss'],
})
export class InvalidInputDialog {
  warning = '';
  ok = '';

  constructor(public dialogRef: MatDialogRef<InvalidInputDialog>, private translation: TranslateService, public dialog : MatDialog) {}

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'Tasks_spinner',
      autoFocus: false,
      disableClose: true
    });

    this.translation.getTranslation(this.translation.defaultLang).subscribe((translation: any) => {

    //close spinner
    this.dialog.getDialogById('Tasks_spinner')?.close();

    this.warning = translation.Tasks.Add.InputWarning;
    this.ok = translation.Tasks.Add.Ok;
    });
  }

  closeDialog(): void {
    this.dialogRef.close();
  }
}
