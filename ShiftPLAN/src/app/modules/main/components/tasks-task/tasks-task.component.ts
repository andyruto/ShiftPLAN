/**
 * tasks-input.component.ts
 * 
 *  Main typescript class for the tasks-task component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-24 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';

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
  taskName = 'Test Task Name'

  constructor(private translate: TranslateService) { }

  ngOnInit(): void {
    this.translate.getTranslation(this.translate.defaultLang).subscribe((translation: any) => { 
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
