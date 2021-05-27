/**
 * bottom-bar.component.ts
 * 
 *  Main typescript class for the bottom-bar component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { Component, HostBinding, Input} from '@angular/core';

@Component({
  selector: 'app-bottom-bar',
  templateUrl: './bottom-bar.component.html',
  styleUrls: ['./bottom-bar.component.scss']
})
export class BottomBarComponent{

  @Input() public tasksBtn: boolean = false
  @HostBinding('class.tasksAllowed')
  get taskGetter(){
    return this.tasksBtn
  }
  
  @Input() public applicationsBtn: boolean = false
  @HostBinding('class.applicationsAllowed')
  get applicationGetter(){
    return this.applicationsBtn
  }
}
