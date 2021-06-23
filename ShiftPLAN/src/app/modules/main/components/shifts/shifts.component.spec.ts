/**
 * shifts.component.spec
 * 
 * Typescript class containing the tests for the 
 * shifts component.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-03 / Anne Naumann
 */
import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ShiftsComponent } from './shifts.component';

describe('ShiftsComponent', () => {
  let component: ShiftsComponent;
  let fixture: ComponentFixture<ShiftsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShiftsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShiftsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
