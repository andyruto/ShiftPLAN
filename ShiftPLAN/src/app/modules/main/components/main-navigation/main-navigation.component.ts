/**
 * main-navigation.component.ts
 * 
 *  Main typescript class for the main-navigation component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-05 / Anne Naumann
 */
import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { SpinnerComponent } from 'src/app/modules/view-elements/spinner/spinner.component';
import { UsertypeService } from 'src/app/services/usertype.service';

@Component({
  selector: 'app-main-navigation',
  templateUrl: './main-navigation.component.html',
  styleUrls: ['./main-navigation.component.scss']
})
export class MainNavigationComponent implements OnInit {

  task: boolean = false;

  constructor(private usertype : UsertypeService, public dialog : MatDialog) { }

  ngOnInit(): void {
    this.checkBtn();
  }

  private async checkBtn() {

    //display spinner
    this.dialog.open(SpinnerComponent, {
      id: 'MainNavigation_spinnerButtonCheck',
      autoFocus: false,
      disableClose: true
    });

    this.task = (await this.usertype.getShown()).tasks;
        
    //close spinner
    this.dialog.getDialogById('MainNavigation_spinnerButtonCheck')?.close();
  }
}
