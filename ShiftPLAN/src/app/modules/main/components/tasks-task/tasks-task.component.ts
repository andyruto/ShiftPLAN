/**
 * tasks-input.component.ts
 * 
 *  Main typescript class for the tasks-task component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { TranslateService } from '@ngx-translate/core';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';

@Component({
  selector: 'app-tasks-task',
  templateUrl: './tasks-task.component.html',
  styleUrls: ['./tasks-task.component.scss']
})
export class TasksTaskComponent implements OnInit {

  title = ''
  editBtn = ''
  labelDescription = ''
  checkBoxOnce = ''
  labelHint = ''
  labelEdit = ''
  labelEditPerson = ''
  labelEditTime = ''

  //boolean that decides which html content is rendered
  oneTimeTask = false
  viewLoaded = false
  taskName = 'Empfang_01'

  constructor(private translate: TranslateService, public dialog: MatDialog) { }

  ngOnInit(): void {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'TasksTask_spinnerTranslationGlobal',
      autoFocus: false,
      disableClose: true
    });

    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 

      //close spinner
      this.dialog.getDialogById('TasksTask_spinnerTranslationGlobal')?.close();

      this.title = translation.Toolbar.Title.AdminAdd;
      this.labelDescription = translation.Tasks.Add.LabelDescription;
      this.checkBoxOnce = translation.Tasks.Add.CheckBoxOnce;
      this.labelHint = translation.Tasks.Add.LabelHint;
      this.labelEdit = translation.Tasks.Task.LabelEdit;
      this.labelEditPerson = translation.Tasks.Task.LabelEditPerson;
      this.labelEditTime = translation.Tasks.Task.LabelEditTime;
      this.editBtn = translation.EditButton;
    });
  }

  ngAfterViewChecked(): void{
    let btn = document.getElementById('editTaskBtn')

    if(btn!.clientHeight != 0 && this.viewLoaded == false){
      let toolbar =  document.getElementsByTagName('mat-toolbar')[0].clientHeight
      let nameInput = document.getElementsByTagName('mat-form-field')[0].clientHeight
      let buttonContainer = document.getElementById('buttonContainer')?.clientHeight
      var inputContainer = document.getElementById('inputContainer')
  
      let screenHeight = window.innerHeight-toolbar-nameInput-buttonContainer!
      inputContainer!.style.height = screenHeight + 'px'

      this.viewLoaded = true
    } 
  }
}
