/**
 * view-elements.module.ts
 * 
 * Module that contains all components that are used multiple times in the application.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-25 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ToolbarComponent } from './toolbar/toolbar.component';

@NgModule({
  declarations: [
    ToolbarComponent,
  ],
  imports: [
    CommonModule,
  ]
})
export class ViewElementsModule { }
